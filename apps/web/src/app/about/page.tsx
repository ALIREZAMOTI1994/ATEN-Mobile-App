import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "About",
};

export default function AboutPage() {
  return (
    <div className="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
      <h1 className="text-3xl font-semibold text-ivory">About ATEN</h1>
      <div className="mt-6 flex flex-col gap-4 text-sm leading-relaxed text-ivory-muted">
        <p>
          ATEN Industrial Connections is a sourcing and supply company for industrial hoses,
          fittings and connections — serving manufacturing, petrochemical, agriculture, food &
          beverage, pharmaceutical and automation industries.
        </p>
        <p>
          We supply suction & transfer hoses, ducting, reinforced PVC lines, rubber and hydraulic
          hoses, pneumatic tubing, silicone products, fire fighting equipment, clamps and fittings,
          and a wide range of specialty hoses — sourced against the specifications published in our
          product catalog.
        </p>
        <p>
          Every product on this platform can be requested through a formal Request for Quotation
          (RFQ) — our sales engineers review each request and respond with a tailored quotation.
          We do not process payments on this platform; all commercial terms are agreed directly
          with our sales team.
        </p>
      </div>
    </div>
  );
}
