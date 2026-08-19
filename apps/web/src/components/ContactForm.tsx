"use client";

import { useState } from "react";
import { ApiError, submitContact } from "@/lib/api";

export function ContactForm() {
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [sent, setSent] = useState(false);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError(null);
    setSubmitting(true);

    const form = new FormData(e.currentTarget);
    try {
      await submitContact({
        name: String(form.get("name") ?? ""),
        company_name: String(form.get("company_name") ?? "") || undefined,
        email: String(form.get("email") ?? ""),
        phone: String(form.get("phone") ?? "") || undefined,
        message: String(form.get("message") ?? ""),
      });
      setSent(true);
      e.currentTarget.reset();
    } catch (err) {
      setError(err instanceof ApiError ? err.message : "Something went wrong. Please try again.");
    } finally {
      setSubmitting(false);
    }
  }

  if (sent) {
    return (
      <p className="rounded-xl border border-border-soft bg-surface p-5 text-sm text-ivory">
        Thank you — your message has been sent. Our team will be in touch shortly.
      </p>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="grid gap-4 sm:grid-cols-2">
      <div>
        <label className="mb-1 block text-xs text-ivory-muted" htmlFor="name">
          Name <span className="text-accent">*</span>
        </label>
        <input
          id="name"
          name="name"
          required
          className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
        />
      </div>
      <div>
        <label className="mb-1 block text-xs text-ivory-muted" htmlFor="company_name">
          Company
        </label>
        <input
          id="company_name"
          name="company_name"
          className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
        />
      </div>
      <div>
        <label className="mb-1 block text-xs text-ivory-muted" htmlFor="email">
          Email <span className="text-accent">*</span>
        </label>
        <input
          id="email"
          name="email"
          type="email"
          required
          className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
        />
      </div>
      <div>
        <label className="mb-1 block text-xs text-ivory-muted" htmlFor="phone">
          Phone
        </label>
        <input
          id="phone"
          name="phone"
          className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
        />
      </div>
      <div className="sm:col-span-2">
        <label className="mb-1 block text-xs text-ivory-muted" htmlFor="message">
          Message <span className="text-accent">*</span>
        </label>
        <textarea
          id="message"
          name="message"
          rows={5}
          required
          className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
        />
      </div>

      {error && <p className="text-sm text-red-400 sm:col-span-2">{error}</p>}

      <button
        type="submit"
        disabled={submitting}
        className="focus-ring rounded-full bg-accent py-3 text-sm font-medium text-ink transition-colors hover:bg-accent-strong disabled:opacity-60 sm:col-span-2"
      >
        {submitting ? "Sending…" : "Send message"}
      </button>
    </form>
  );
}
