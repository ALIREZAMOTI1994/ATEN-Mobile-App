import type { SpecTable as SpecTableType } from "@/lib/types";

export function SpecTable({ specs }: { specs: SpecTableType }) {
  return (
    <div className="overflow-x-auto rounded-xl border border-border-soft">
      <table className="w-full min-w-[420px] border-collapse text-sm">
        <thead>
          <tr className="bg-surface">
            {specs.columns.map((col) => (
              <th
                key={col}
                className="border-b border-border-soft px-4 py-2.5 text-start text-xs font-medium uppercase tracking-wide text-ivory-muted"
              >
                {col}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {specs.rows.map((row, i) => (
            <tr key={i} className="odd:bg-transparent even:bg-surface/50">
              {row.map((cell, j) => (
                <td key={j} className="border-b border-border-soft/60 px-4 py-2 text-ivory">
                  {cell}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
