import type { Metadata, Viewport } from "next";
import { Inter } from "next/font/google";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { RfqBar } from "@/components/RfqBar";
import { ServiceWorkerRegister } from "@/components/ServiceWorkerRegister";
import "./globals.css";
import { Providers } from "./providers";

export const metadata: Metadata = {
  title: {
    default: "ATEN Industrial Connections",
    template: "%s · ATEN Industrial Connections",
  },
  description:
    "ATEN supplies industrial hoses, fittings and connections for manufacturing, petrochemical, agriculture and more. Request a formal quotation online.",
  manifest: "/manifest.webmanifest",
  appleWebApp: {
    capable: true,
    statusBarStyle: "black-translucent",
    title: "ATEN",
  },
  icons: {
    icon: [{ url: "/favicon-32.png", sizes: "32x32", type: "image/png" }],
    apple: [{ url: "/apple-touch-icon.png", sizes: "180x180", type: "image/png" }],
  },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  themeColor: "#0a0a0b",
};

const inter = Inter({ variable: "--font-inter", subsets: ["latin"] });

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`h-full antialiased ${inter.variable}`} suppressHydrationWarning>
      <body className="flex min-h-full flex-col bg-ink text-ivory">
        <Providers>
          <ServiceWorkerRegister />
          <Header />
          <main className="flex-1">{children}</main>
          <Footer />
          <RfqBar />
        </Providers>
      </body>
    </html>
  );
}
