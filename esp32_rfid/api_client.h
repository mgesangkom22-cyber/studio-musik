#ifndef API_CLIENT_H
#define API_CLIENT_H

#include <WiFi.h>
#include <HTTPClient.h>
#include "config.h"
#include "buzzer.h"

// ================================================================
// DUKUNGAN KOMUNIKASI HTTP ESP32 ↔ LARAVEL & TRIGER BUZZER
// Validasi Sistem Laravel Menentukan Indikator Buzzer:
// - "beep": "success" -> 1 Bip Panjang (BERHASIL)
// - "beep": "fail"    -> 3 Bip Pendek (GAGAL)
// - "beep": "none"    -> Tidak Berbunyi (TIDAK ADA HALAMAN SCAN AKTIF)
// ================================================================

inline bool sendRegisterUID(String uid)
{
    if (WiFi.status() != WL_CONNECTED)
    {
        Serial.println("WiFi belum terhubung");
        Serial.println("[Buzzer] Koneksi Gagal -> 3 Bip Pendek");
        failBeep();
        return false;
    }

    HTTPClient http;

    http.begin(API_SCAN);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("X-ESP32-TOKEN", ESP32_TOKEN);
    http.setTimeout(3000);

    String body = "{\"uid_rfid\":\"" + uid + "\"}";

    Serial.println();
    Serial.println("Mengirim ke Laravel...");
    Serial.println(body);

    int code = http.POST(body);

    Serial.print("HTTP Code : ");
    Serial.println(code);

    if (code <= 0 || code >= 500)
    {
        Serial.println("Gagal mengirim ke Laravel (Server Error).");
        Serial.println("[Buzzer] Server Error -> 3 Bip Pendek");
        failBeep();
        http.end();
        return false;
    }

    String response = http.getString();

    Serial.println("Response:");
    Serial.println(response);

    http.end();

    // ------------------------------------------------------------
    // EKSEKUSI INDIKATOR BUZZER BERDASARKAN HASIL VALIDASI LARAVEL
    // ------------------------------------------------------------
    if (response.indexOf("\"beep\":\"success\"") != -1)
    {
        Serial.println("[Buzzer] Validasi Sistem BERHASIL -> 1 Bip Panjang");
        successBeep(); // 1 Bip Panjang
        return true;
    }
    else if (response.indexOf("\"beep\":\"fail\"") != -1)
    {
        Serial.println("[Buzzer] Validasi Sistem GAGAL / DITOLAK -> 3 Bip Pendek");
        failBeep(); // 3 Bip Pendek
        return false;
    }
    else if (response.indexOf("\"beep\":\"none\"") != -1)
    {
        Serial.println("[Buzzer] Tidak Ada Halaman Scan Aktif -> Diam (0 Beep)");
        // Tidak berbunyi sama sekali
        return false;
    }
    else
    {
        if (code == 200 && response.indexOf("\"success\":true") != -1)
        {
            Serial.println("[Buzzer] Respon Success -> 1 Bip Panjang");
            successBeep();
            return true;
        }
        else
        {
            Serial.println("[Buzzer] Respon Fail -> 3 Bip Pendek");
            failBeep();
            return false;
        }
    }
}

#endif
