"use client";

import { createContext, useCallback, useContext, useEffect, useMemo, useState } from "react";
import { type Lang, dictionary } from "./i18n";

type LanguageContextValue = {
  lang: Lang;
  setLang: (lang: Lang) => void;
  dict: (typeof dictionary)[Lang];
};

const LanguageContext = createContext<LanguageContextValue | null>(null);
const STORAGE_KEY = "aten:lang";

export function LanguageProvider({ children }: { children: React.ReactNode }) {
  const [lang, setLangState] = useState<Lang>("en");

  useEffect(() => {
    // Read the persisted preference after mount so the server-rendered
    // markup (always "en") matches the client's first paint, avoiding a
    // hydration mismatch; this is the one-time exception to "no setState in
    // effects".
    const stored = window.localStorage.getItem(STORAGE_KEY) as Lang | null;
    if (stored === "en" || stored === "fa") {
      // eslint-disable-next-line react-hooks/set-state-in-effect
      setLangState(stored);
    }
  }, []);

  useEffect(() => {
    document.documentElement.lang = lang;
    document.documentElement.dir = dictionary[lang].dir;
  }, [lang]);

  const setLang = useCallback((next: Lang) => {
    setLangState(next);
    window.localStorage.setItem(STORAGE_KEY, next);
  }, []);

  const value = useMemo(() => ({ lang, setLang, dict: dictionary[lang] }), [lang, setLang]);

  return <LanguageContext.Provider value={value}>{children}</LanguageContext.Provider>;
}

export function useLanguage() {
  const ctx = useContext(LanguageContext);
  if (!ctx) throw new Error("useLanguage must be used within LanguageProvider");
  return ctx;
}
