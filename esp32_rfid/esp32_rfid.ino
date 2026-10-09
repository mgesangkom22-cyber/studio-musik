#include <WiFi.h>
#include <SPI.h>
#include <MFRC522.h>

#include "config.h"
#include "buzzer.h"
#include "api_client.h"

// RFID object
MFRC522 mfrc522(SS_PIN, RST_PIN);

// Timer reconnect WiFi, Debounce tracking, & RFID self-healing
unsigned long lastWifiCheck = 0;
String lastScannedUid = "";
unsigned long lastScanTime = 0;
unsigned long lastRfidReset = 0;

void connectWiFi()
{
    Serial.println("Menghubungkan WiFi...");

    WiFi.mode(WIFI_STA);
    WiFi.setAutoReconnect(true);
    WiFi.setSleep(false); // Nonaktifkan WiFi sleep mode untuk stabilitas sinyal

    WiFi.begin(WIFI_SSID, WIFI_PASSWORD);

    int count = 0;
    while (WiFi.status() != WL_CONNECTED && count < 20)
    {
        delay(500);
        Serial.print(".");
        count++;
    }

    if (WiFi.status() == WL_CONNECTED)
    {
        Serial.println();
        Serial.println("===========================");
        Serial.println("WiFi Terhubung");
        Serial.print("IP ESP32 : ");
        Serial.println(WiFi.localIP());
        Serial.println("===========================");
    }
    else
    {
        Serial.println();
        Serial.println("Gagal terhubung ke WiFi saat startup. Akan dicoba ulang di background loop...");
    }
}

void setup()
{
    Serial.begin(115200);
    delay(1000);
    Serial.println();
    Serial.println("===========================");
    Serial.println("ESP32 START");
    Serial.println("===========================");
    Serial.flush();

    // 1. Inisialisasi Active Buzzer (GPIO 26)
    initBuzzer();

    // 2. WiFi
    connectWiFi();
    lastWifiCheck = millis();

    // 3. SPI
    SPI.begin(18, 19, 23, 5);

    // 4. RFID
    mfrc522.PCD_Init();

    byte version = mfrc522.PCD_ReadRegister(MFRC522::VersionReg);

    Serial.print("Versi RC522 : 0x");
    Serial.println(version, HEX);

    if (version == 0x00 || version == 0xFF)
    {
        Serial.println("RC522 Tidak Terdeteksi!");

        while (true)
            ;
    }

    Serial.println("RC522 Berhasil Terdeteksi");
    Serial.println();
    Serial.println("Silakan Tempelkan Kartu RFID...");
}

void loop()
{
    // Reconnect WiFi jika putus
    if (WiFi.status() != WL_CONNECTED)
    {
        if (millis() - lastWifiCheck >= 7000)
        {
            lastWifiCheck = millis();
            Serial.println("WiFi Putus, reconnecting...");
            wifiLostBeep();
            WiFi.disconnect();
            WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
        }
        return;
    }

    // Self-healing RC522: Refresh registri RC522 setiap 10 detik agar sensor tidak freeze/macet
    if (millis() - lastRfidReset >= 10000)
    {
        lastRfidReset = millis();
        mfrc522.PCD_Init();
    }

    // Cek kartu baru
    if (!mfrc522.PICC_IsNewCardPresent())
        return;

    if (!mfrc522.PICC_ReadCardSerial())
        return;

    String uid = "";

    for (byte i = 0; i < mfrc522.uid.size; i++)
    {
        if (mfrc522.uid.uidByte[i] < 0x10)
            uid += "0";

        uid += String(mfrc522.uid.uidByte[i], HEX);
    }

    uid.toUpperCase();

    // Debounce: Jika UID sama discan ulang dalam waktu < 2.5 detik, abaikan untuk mencegah spam/double beep
    if (uid == lastScannedUid && (millis() - lastScanTime < 2500))
    {
        mfrc522.PICC_HaltA();
        mfrc522.PCD_StopCrypto1();
        return;
    }

    lastScannedUid = uid;
    lastScanTime = millis();

    Serial.println();
    Serial.println("===========================");
    Serial.print("UID : ");
    Serial.println(uid);

    // KIRIM KE LARAVEL (Buzzer berbunyi berdasarkan bidang "beep" dari respon JSON Laravel)
    bool ok = sendRegisterUID(uid);

    if (ok)
    {
        Serial.println("UID berhasil dikirim & diproses Laravel");
    }
    else
    {
        Serial.println("Gagal kirim / Ditolak / Tidak Aktif / Tidak Ada Halaman Scan Aktif");
    }

    Serial.println("===========================");

    // Reset & Re-init sensor RFID agar selalu siap untuk pembacaan berikutnya
    mfrc522.PICC_HaltA();
    mfrc522.PCD_StopCrypto1();
    mfrc522.PCD_Init();

    // Tunda 1.5 detik agar tidak spam saat kartu menempel terus
    delay(1500);
}
