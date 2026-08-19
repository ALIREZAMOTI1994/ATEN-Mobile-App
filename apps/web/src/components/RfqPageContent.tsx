"use client";

import Link from "next/link";
import { useState } from "react";
import { SmartImage } from "@/components/SmartImage";
import { ApiError, productImageUrl, submitRfq } from "@/lib/api";
import { useLanguage } from "@/lib/language-context";
import { useRfqCart } from "@/lib/rfq-cart";
import type { Rfq, RfqItemInput } from "@/lib/types";

export function RfqPageContent() {
  const { dict } = useLanguage();
  const { items, remove, updateQuantity, clear } = useRfqCart();
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [result, setResult] = useState<Rfq | null>(null);

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setError(null);
    setSubmitting(true);

    const form = new FormData(e.currentTarget);
    const rfqItems: RfqItemInput[] = items.map((item) => ({
      product_slug: item.productSlug,
      product_name: item.name,
      quantity: item.quantity,
      unit: item.unit,
      size: item.size,
    }));

    try {
      const { data } = await submitRfq({
        type: items.length > 1 ? "multi" : "single",
        company_name: String(form.get("company_name") ?? ""),
        contact_name: String(form.get("contact_name") ?? ""),
        email: String(form.get("email") ?? ""),
        phone: String(form.get("phone") ?? "") || undefined,
        country: String(form.get("country") ?? "") || undefined,
        message: String(form.get("message") ?? "") || undefined,
        items: rfqItems,
      });
      setResult(data);
      clear();
    } catch (err) {
      setError(err instanceof ApiError ? err.message : "Something went wrong. Please try again.");
    } finally {
      setSubmitting(false);
    }
  }

  if (result) {
    return (
      <div className="mx-auto max-w-lg px-4 py-20 text-center sm:px-6">
        <div className="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-accent-soft text-accent">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
            <path d="M5 13l4 4L19 7" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
        <h1 className="text-xl font-semibold text-ivory">{dict.rfq.successTitle}</h1>
        <p className="mt-2 text-2xl font-semibold tracking-wide text-accent">{result.rfq_number}</p>
        <p className="mt-3 text-sm text-ivory-muted">{dict.rfq.trackHint}</p>
        <Link
          href="/products"
          className="focus-ring mt-8 inline-block rounded-full bg-accent px-6 py-3 text-sm font-medium text-ink hover:bg-accent-strong"
        >
          {dict.rfq.browse}
        </Link>
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="mx-auto max-w-md px-4 py-20 text-center sm:px-6">
        <p className="text-sm text-ivory-muted">{dict.rfq.empty}</p>
        <Link
          href="/products"
          className="focus-ring mt-6 inline-block rounded-full bg-accent px-6 py-3 text-sm font-medium text-ink hover:bg-accent-strong"
        >
          {dict.rfq.browse}
        </Link>
      </div>
    );
  }

  return (
    <div className="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
      <h1 className="text-2xl font-semibold text-ivory">{dict.rfq.title}</h1>
      <p className="mt-1 text-sm text-ivory-muted">{dict.rfq.subtitle}</p>

      <div className="mt-8 flex flex-col gap-3">
        {items.map((item) => (
          <div
            key={item.productSlug}
            className="flex items-center gap-4 rounded-xl border border-border-soft bg-surface p-3"
          >
            <div className="relative h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-ink">
              <SmartImage src={productImageUrl(item.thumbnail)} alt="" fill sizes="64px" className="object-cover" />
            </div>
            <div className="flex-1">
              <p className="text-sm font-medium text-ivory">{item.name}</p>
              <p className="text-xs text-ivory-muted">{item.sku}</p>
            </div>
            <label className="flex items-center gap-1.5 text-xs text-ivory-muted">
              {dict.rfq.quantity}
              <input
                type="number"
                min={1}
                value={item.quantity}
                onChange={(e) => updateQuantity(item.productSlug, Number(e.target.value))}
                className="focus-ring w-16 rounded border border-border bg-ink px-2 py-1 text-ivory"
              />
            </label>
            <button
              type="button"
              onClick={() => remove(item.productSlug)}
              className="focus-ring text-xs text-ivory-muted hover:text-ivory"
            >
              {dict.rfq.remove}
            </button>
          </div>
        ))}
      </div>

      <form onSubmit={handleSubmit} className="mt-8 grid gap-4 sm:grid-cols-2">
        <Field name="company_name" label={dict.rfq.company} required />
        <Field name="contact_name" label={dict.rfq.contact} required />
        <Field name="email" label={dict.rfq.email} type="email" required />
        <Field name="phone" label={dict.rfq.phone} />
        <Field name="country" label={dict.rfq.country} />
        <div className="sm:col-span-2">
          <label className="mb-1 block text-xs text-ivory-muted">{dict.rfq.message}</label>
          <textarea
            name="message"
            rows={4}
            className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
          />
        </div>

        {error && <p className="text-sm text-red-400 sm:col-span-2">{error}</p>}

        <button
          type="submit"
          disabled={submitting}
          className="focus-ring rounded-full bg-accent py-3.5 text-sm font-medium text-ink transition-colors hover:bg-accent-strong disabled:opacity-60 sm:col-span-2"
        >
          {submitting ? dict.rfq.submitting : dict.rfq.submit}
        </button>
      </form>
    </div>
  );
}

function Field({
  name,
  label,
  type = "text",
  required,
}: {
  name: string;
  label: string;
  type?: string;
  required?: boolean;
}) {
  return (
    <div>
      <label className="mb-1 block text-xs text-ivory-muted" htmlFor={name}>
        {label}
        {required && <span className="text-accent"> *</span>}
      </label>
      <input
        id={name}
        name={name}
        type={type}
        required={required}
        className="focus-ring w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-ivory"
      />
    </div>
  );
}
