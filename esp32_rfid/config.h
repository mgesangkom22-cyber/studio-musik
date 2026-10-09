#ifndef CONFIG_H
#define CONFIG_H

// =====================
// WIFI
// =====================
const char* WIFI_SSID = "Hasyim Home";
const char* WIFI_PASSWORD = "01092002";

// =====================
// LARAVEL SERVER
// =====================
const char* API_REGISTER = "http://192.168.1.12/api/rfid/register";
const char* API_SCAN     = "http://192.168.1.12/api/rfid/scan";

// =====================
// TOKEN
// =====================
const char* ESP32_TOKEN  = "UNU_STUDIO_RFID_2026";

// =====================
// RC522 PIN
// =====================
#define SS_PIN 5
#define RST_PIN 22

// =====================
// ACTIVE BUZZER PIN (GPIO 26)
// =====================
#define BUZZER_PIN 26

#endif
