import { createI18n } from 'vue-i18n';

// Fungsi untuk membuat instance i18n
export function setupI18n(options = { locale: 'en', translations: {} }) {
  const i18n = createI18n({
    legacy: false, // Gunakan Composition API
    locale: options.locale,
    fallbackLocale: 'en',
    messages: options.translations
  });
  
  return i18n;
}

// Fungsi untuk mengubah bahasa
export function setLocale(i18n, locale) {
  if (i18n.global.availableLocales.includes(locale)) {
    i18n.global.locale.value = locale;
    localStorage.setItem('locale', locale);
    return true;
  }
  
  return false;
}

// Fungsi untuk mendapatkan locale dari localStorage atau default
export function getStoredLocale() {
  return localStorage.getItem('locale') || 'en';
}
