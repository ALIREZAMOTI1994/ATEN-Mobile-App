import type { Metadata } from "next";
import { ContactForm } from "@/components/ContactForm";

export const metadata: Metadata = {
  title: "Contact",
};

export default function ContactPage() {
  return (
    <div className="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
      <h1 className="text-3xl font-semibold text-ivory">Contact ATEN</h1>
      <p className="mt-3 text-sm text-ivory-muted">
        For quotations, use the RFQ workflow on any product. For general enquiries, reach us
        directly or send a message below.
      </p>

      <dl className="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div className="rounded-xl border border-border-soft bg-surface p-4">
          <dt className="text-xs text-ivory-muted">Email</dt>
          <dd className="mt-1 text-sm text-ivory">sales@atenlink.com</dd>
        </div>
        <div className="rounded-xl border border-border-soft bg-surface p-4">
          <dt className="text-xs text-ivory-muted">Business hours</dt>
          <dd className="mt-1 text-sm text-ivory">Sat–Thu, 9:00–18:00</dd>
        </div>
        <div className="rounded-xl border border-border-soft bg-surface p-4">
          <dt className="text-xs text-ivory-muted">Address</dt>
          <dd className="mt-1 text-sm text-ivory">Tehran, Iran</dd>
        </div>
      </dl>

      <div className="mt-10">
        <ContactForm />
      </div>
    </div>
  );
}
