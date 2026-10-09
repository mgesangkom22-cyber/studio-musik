#include "api_client.h"
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include "config.h"
#include "buzzer.h"

// ================================================================
// PENGIRIMAN DATA RFID KE LARAVEL & RESPON INDIKATOR BUZZER
// Validasi Sistem Laravel Menentukan Indikator Buzzer:
// - BERHASIL (1 Bip Panjang)
// - GAGAL    (3 Bip Pendek)
// ================================================================

void sendRfidScan(String uid) {
    if (WiFi.status() != WL_CONNECTED) {
        Serial.println("[API Error] WiFi terputus saat mencoba mengirim RFID!");
        failBeep(); // 3 Bip Pendek
        return;
    }

    HTTPClient http;
    http.begin(API_SCAN);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-ESP32-TOKEN", ESP32_TOKEN);
    http.setTimeout(5000);

    StaticJsonDocument<128> docReq;
    docReq["uid_rfid"] = uid;
    String jsonPayload;
    serializeJson(docReq, jsonPayload);

    Serial.print("[API] Mengirim UID RFID ke Laravel: ");
    Serial.println(uid);

    int httpCode = http.POST(jsonPayload);
    String responseStr = http.getString();
    http.end();

    Serial.print("[API] HTTP Response Code: ");
    Serial.println(httpCode);
    Serial.print("[API] Payload: ");
    Serial.println(responseStr);

    if (httpCode <= 0 || httpCode >= 500) {
        Serial.println("[Buzzer] Server Error atau HTTP Gagal -> 3 Bip Pendek");
        failBeep();
        return;
    }

    StaticJsonDocument<512> docRes;
    DeserializationError error = deserializeJson(docRes, responseStr);

    if (error) {
        Serial.println("[API Error] Gagal parse JSON dari Laravel!");
        failBeep();
        return;
    }

    bool isSuccess = docRes["success"] | false;
    String status = docRes["status"] | "";
    String statusAnggota = docRes["anggota"]["status_anggota"] | "";

    if (httpCode == 200 && isSuccess && (status == "success" || statusAnggota.equalsIgnoreCase("Aktif"))) {
        Serial.println("[Buzzer] Validasi Sistem BERHASIL -> 1 Bip Panjang");
        successBeep(); // 1 Bip Panjang
    } else {
        Serial.println("[Buzzer] Validasi Sistem GAGAL / DITOLAK -> 3 Bip Pendek");
        failBeep(); // 3 Bip Pendek
    }
}
