"use client";

import { useState } from "react";
import { ApiError, trackRfq } from "@/lib/api";
import { useLanguage } from "@/lib/language-context";
import type { Rfq } from "@/lib/types";

export function RfqTrackContent() {
  const { dict } = useLanguage();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [rfq, setRfq] = useState<Rfq | null>(null);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError(null);
    setLoading(true);
    setRfq(null);

    const form = new FormData(e.currentTarget);
    try {
      const { data } = await trackRfq(String(form.get("rfq_number")), String(form.get("email")));
      setRfq(data);
    } catch (err) {
      setError(
        err instanceof ApiError && err.status === 404
          ? "No RFQ found for that number and email."
          : "Something went wrong. Please try again.",
      );
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="mx-auto max-w-lg px-4 py-14 sm:px-6">
      <h1 className="text-2xl font-semibold text-ivory">{dict.rfq.trackTitle}</h1>

      <form onSubmit={handleSubmit} className="mt-6 flex flex-col gap-4">
        <input
          name="rfq_number"
          placeholder="RFQ-2026-XXXXXX"
          required
          className="focus-ring rounded-lg border border-border bg-surface px-3 py-2.5 text-sm text-ivory placeholder:text-ivory-muted"
        />
        <input
          name="email"
          type="email"
          placeholder={dict.rfq.email}
          required
          className="focus-ring rounded-lg border border-border bg-surface px-3 py-2.5 text-sm text-ivory placeholder:text-ivory-muted"
        />
        <button
          type="submit"
          disabled={loading}
          className="focus-ring rounded-full bg-accent py-3 text-sm font-medium text-ink hover:bg-accent-strong disabled:opacity-60"
        >
          {loading ? dict.rfq.submitting : dict.rfq.trackButton}
        </button>
      </form>

      {error && <p className="mt-4 text-sm text-red-400">{error}</p>}

      {rfq && (
        <div className="mt-8 rounded-xl border border-border-soft bg-surface p-5">
          <div className="flex items-center justify-between">
            <span className="text-sm font-semibold text-ivory">{rfq.rfq_number}</span>
            <span className="rounded-full bg-accent-soft px-3 py-1 text-xs font-medium text-accent">
              {rfq.status}
            </span>
          </div>
          <dl className="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div>
              <dt className="text-ivory-muted">Company</dt>
              <dd className="text-ivory">{rfq.company_name}</dd>
            </div>
            <div>
              <dt className="text-ivory-muted">Submitted</dt>
              <dd className="text-ivory">
                {rfq.submitted_at ? new Date(rfq.submitted_at).toLocaleDateString() : "—"}
              </dd>
            </div>
          </dl>
          <ul className="mt-4 flex flex-col gap-2 border-t border-border-soft pt-4">
            {rfq.items?.map((item, i) => (
              <li key={i} className="flex justify-between text-sm">
                <span className="text-ivory">{item.product_name}</span>
                <span className="text-ivory-muted">
                  {item.quantity} {item.unit}
                </span>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}
