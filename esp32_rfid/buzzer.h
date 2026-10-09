#ifndef BUZZER_H
#define BUZZER_H

#include <Arduino.h>
#include "config.h"

// ================================================================
// MODUL INDIKATOR ACTIVE BUZZER (GPIO 26)
// Sesuai Aturan Sistem:
// 1. BERHASIL -> 1 Bip Panjang (600ms)
// 2. GAGAL    -> 3 Bip Pendek (120ms / 100ms)
// ================================================================

#define BUZZER_ON  HIGH
#define BUZZER_OFF LOW

inline void initBuzzer() {
    pinMode(BUZZER_PIN, OUTPUT);
    digitalWrite(BUZZER_PIN, BUZZER_OFF); // Pastikan buzzer mati di awal
}

// Helper bunyi Active Buzzer (murni menggunakan digitalWrite)
inline void beep(unsigned int durationMs) {
    digitalWrite(BUZZER_PIN, BUZZER_ON);  // Nyalakan Buzzer
    delay(durationMs);
    digitalWrite(BUZZER_PIN, BUZZER_OFF); // Matikan Buzzer
}

// ----------------------------------------------------------------
// POLA A: BERHASIL -> 1 Bip Panjang (600ms)
// ----------------------------------------------------------------
inline void successBeep() {
    beep(600);
}

// ----------------------------------------------------------------
// POLA B: GAGAL -> 3 Bip Pendek (120ms - 100ms - 120ms - 100ms - 120ms)
// ----------------------------------------------------------------
inline void failBeep() {
    beep(120);
    delay(100);
    beep(120);
    delay(100);
    beep(120);
}

// Alias fungsi indikator lama (semua skenario gagal mengarah ke failBeep)
inline void notRegisteredBeep() { failBeep(); }
inline void suspendedBeep()     { failBeep(); }
inline void inactiveBeep()      { failBeep(); }
inline void serverErrorBeep()   { failBeep(); }
inline void wifiLostBeep()      { failBeep(); }

#endif // BUZZER_H
