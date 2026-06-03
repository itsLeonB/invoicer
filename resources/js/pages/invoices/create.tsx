import { Head } from '@inertiajs/react';
import { Eye, Plus, Send, Trash2 } from 'lucide-react';
import { type FormEvent, useRef, useState } from 'react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { DatePicker } from '@/components/ui/date-picker';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';

type LineItem = {
    description: string;
    quantity: number;
    unit_price: number;
};

type Errors = Record<string, string>;

export default function Create() {
    const [items, setItems] = useState<LineItem[]>([
        { description: '', quantity: 1, unit_price: 0 },
    ]);
    const [processing, setProcessing] = useState(false);
    const [previewing, setPreviewing] = useState(false);
    const [errors, setErrors] = useState<Errors>({});
    const [customerName, setCustomerName] = useState('');
    const [customerMobile, setCustomerMobile] = useState('');
    const [date, setDate] = useState(new Date().toISOString().split('T')[0]);
    const formRef = useRef<HTMLFormElement>(null);

    const total = items.reduce(
        (sum, item) => sum + item.quantity * item.unit_price,
        0,
    );

    const isFormValid =
        customerName.trim() !== '' &&
        customerMobile.trim() !== '' &&
        items.every((item) => item.description.trim() !== '' && item.quantity > 0 && item.unit_price > 0);

    const updateItem = (index: number, field: keyof LineItem, value: string) => {
        setItems((prev) =>
            prev.map((item, i) => {
                if (i !== index) return item;
                if (field === 'description') return { ...item, [field]: value };
                return { ...item, [field]: Number(value) || 0 };
            }),
        );
    };

    const addItem = () => {
        setItems((prev) => [...prev, { description: '', quantity: 1, unit_price: 0 }]);
    };

    const removeItem = (index: number) => {
        if (items.length > 1) {
            setItems((prev) => prev.filter((_, i) => i !== index));
        }
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const getFormData = () => {
        if (!formRef.current) return null;
        return {
            date,
            customer_name: customerName,
            customer_mobile: customerMobile,
            items,
        };
    };

    const submitToEndpoint = async (url: string, setLoading: (v: boolean) => void) => {
        const data = getFormData();
        if (!data) return;

        setLoading(true);
        setErrors({});

        const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/pdf',
                },
                body: JSON.stringify(data),
            });

            if (response.status === 422) {
                const json = await response.json();
                setErrors(json.errors || {});
                return;
            }

            if (!response.ok) {
                setErrors({ items: 'Something went wrong. Please try again.' });
                return;
            }

            const blob = await response.blob();
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, '_blank');
        } finally {
            setLoading(false);
        }
    };

    const handlePreview = () => {
        submitToEndpoint('/invoices/preview', setPreviewing);
    };

    const handleGenerate = (e: FormEvent) => {
        e.preventDefault();
        submitToEndpoint('/invoices', setProcessing);
    };

    return (
        <>
            <Head title="Create Invoice" />

            <div className="mx-auto w-full max-w-6xl p-4">
                {/* Header */}
                <div className="mb-6 flex items-center justify-between">
                    <h2 className="text-2xl font-bold tracking-tight">Create New Invoice</h2>
                    <Button
                        type="button"
                        variant="outline"
                        onClick={handlePreview}
                        disabled={previewing || !isFormValid}
                    >
                        {previewing ? <Spinner /> : <Eye />}
                        Preview
                    </Button>
                </div>

                <form ref={formRef} onSubmit={handleGenerate}>
                    <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
                        {/* Left Column: Form */}
                        <div className="space-y-6 lg:col-span-8">
                            {/* Customer Details Card */}
                            <section className="rounded-xl border bg-card p-6">
                                <h3 className="mb-4 text-base font-semibold">Customer Details</h3>
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label htmlFor="customer_name">Customer Name</Label>
                                        <Input
                                            id="customer_name"
                                            name="customer_name"
                                            placeholder="e.g. John Doe"
                                            value={customerName}
                                            onChange={(e) => setCustomerName(e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.customer_name} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="customer_mobile">Mobile Number</Label>
                                        <Input
                                            id="customer_mobile"
                                            name="customer_mobile"
                                            placeholder="e.g. 0812 3456 7890"
                                            value={customerMobile}
                                            onChange={(e) => setCustomerMobile(e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.customer_mobile} />
                                    </div>
                                </div>
                            </section>

                            {/* Purchased Items Card */}
                            <section className="rounded-xl border bg-card p-6">
                                <div className="mb-4 flex items-center justify-between">
                                    <h3 className="text-base font-semibold">Purchased Items</h3>
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        onClick={addItem}
                                        className="text-primary"
                                    >
                                        <Plus /> Add Item
                                    </Button>
                                </div>
                                <InputError message={errors.items} />

                                <div className="overflow-x-auto">
                                    <table className="w-full text-left text-sm">
                                        <thead>
                                            <tr className="border-b text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                                <th className="pb-3 pr-2">Description</th>
                                                <th className="w-20 pb-3 pr-2">Qty</th>
                                                <th className="w-32 pb-3 pr-2">Unit Price</th>
                                                <th className="w-32 pb-3 pr-2 text-right">Total</th>
                                                <th className="w-10 pb-3"></th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y">
                                            {items.map((item, index) => (
                                                <tr key={index} className="group">
                                                    <td className="py-3 pr-2">
                                                        <Input
                                                            placeholder="Item description"
                                                            value={item.description}
                                                            onChange={(e) => updateItem(index, 'description', e.target.value)}
                                                            className="border-transparent bg-transparent shadow-none focus-visible:border-input focus-visible:bg-background"
                                                            required
                                                        />
                                                        <InputError message={errors[`items.${index}.description`]} />
                                                    </td>
                                                    <td className="py-3 pr-2">
                                                        <Input
                                                            type="number"
                                                            min={1}
                                                            value={item.quantity}
                                                            onChange={(e) => updateItem(index, 'quantity', e.target.value)}
                                                            className="border-transparent bg-transparent tabular-nums shadow-none focus-visible:border-input focus-visible:bg-background"
                                                            required
                                                        />
                                                        <InputError message={errors[`items.${index}.quantity`]} />
                                                    </td>
                                                    <td className="py-3 pr-2">
                                                        <Input
                                                            type="number"
                                                            min={0}
                                                            value={item.unit_price}
                                                            onChange={(e) => updateItem(index, 'unit_price', e.target.value)}
                                                            className="border-transparent bg-transparent tabular-nums shadow-none focus-visible:border-input focus-visible:bg-background"
                                                            required
                                                        />
                                                        <InputError message={errors[`items.${index}.unit_price`]} />
                                                    </td>
                                                    <td className="py-3 pr-2 text-right tabular-nums font-medium">
                                                        {formatCurrency(item.quantity * item.unit_price)}
                                                    </td>
                                                    <td className="py-3 text-right">
                                                        <Button
                                                            type="button"
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={() => removeItem(index)}
                                                            disabled={items.length === 1}
                                                            className="opacity-0 group-hover:opacity-100"
                                                            aria-label="Remove item"
                                                        >
                                                            <Trash2 className="size-4 text-destructive" />
                                                        </Button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        {/* Right Column: Summary */}
                        <div className="lg:col-span-4">
                            <div className="sticky top-4 space-y-6">
                                <section className="rounded-xl border bg-card p-6">
                                    <h3 className="mb-4 text-base font-semibold">Invoice Summary</h3>

                                    <div className="mb-2 space-y-2">
                                        <Label htmlFor="date">Transaction Date</Label>
                                        <DatePicker value={date} onChange={setDate} />
                                        <InputError message={errors.date} />
                                    </div>

                                    <div className="my-4 border-t pt-4">
                                        <div className="flex items-center justify-between">
                                            <span className="text-sm font-medium text-muted-foreground">Total Amount</span>
                                            <span className="text-2xl font-bold tabular-nums text-primary">
                                                {formatCurrency(total)}
                                            </span>
                                        </div>
                                    </div>

                                    <Button
                                        type="submit"
                                        className="mt-4 w-full"
                                        size="lg"
                                        disabled={processing || !isFormValid}
                                    >
                                        {processing ? <Spinner /> : <Send />}
                                        Generate Invoice
                                    </Button>
                                </section>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </>
    );
}

Create.layout = {
    breadcrumbs: [
        {
            title: 'Create Invoice',
            href: '/',
        },
    ],
};
