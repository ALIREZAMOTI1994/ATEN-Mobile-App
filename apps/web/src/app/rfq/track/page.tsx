import type { Metadata } from "next";
import { RfqTrackContent } from "@/components/RfqTrackContent";

export const metadata: Metadata = {
  title: "Track an RFQ",
};

export default function RfqTrackPage() {
  return <RfqTrackContent />;
}
