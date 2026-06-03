import { CalendarDays } from 'lucide-react';
import { useRef } from 'react';
import { cn } from '@/lib/utils';

function formatDateId(dateStr: string): string {
    if (!dateStr) return '';
    const d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

export function DatePicker({
    value,
    onChange,
    className,
}: {
    value: string;
    onChange: (value: string) => void;
    className?: string;
}) {
    const inputRef = useRef<HTMLInputElement>(null);

    return (
        <div
            className={cn(
                'relative flex h-9 w-full cursor-pointer items-center rounded-md border border-input bg-background px-3 py-1 text-sm shadow-xs',
                'focus-within:border-ring focus-within:ring-ring/50 focus-within:ring-[3px]',
                className,
            )}
            onClick={() => inputRef.current?.showPicker()}
        >
            <CalendarDays className="mr-2 size-4 shrink-0 text-muted-foreground" />
            <span className={value ? 'text-foreground' : 'text-muted-foreground'}>
                {value ? formatDateId(value) : 'Pick a date'}
            </span>
            <input
                ref={inputRef}
                type="date"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                required
            />
        </div>
    );
}
