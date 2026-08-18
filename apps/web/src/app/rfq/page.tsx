import type { Metadata } from "next";
import { RfqPageContent } from "@/components/RfqPageContent";

export const metadata: Metadata = {
  title: "Request for Quotation",
};

export default function RfqPage() {
  return <RfqPageContent />;
}
