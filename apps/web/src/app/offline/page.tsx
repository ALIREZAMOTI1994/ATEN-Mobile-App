export default function OfflinePage() {
  return (
    <div className="mx-auto flex min-h-[60vh] max-w-md flex-col items-center justify-center gap-3 px-4 text-center">
      <h1 className="text-xl font-semibold text-ivory">You&apos;re offline</h1>
      <p className="text-sm text-ivory-muted">
        This page isn&apos;t available offline yet. Check your connection and try again — items you
        already added to your RFQ list are saved on this device.
      </p>
    </div>
  );
}
