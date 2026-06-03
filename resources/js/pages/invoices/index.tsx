import { Head } from '@inertiajs/react';
import { Download, Eye } from 'lucide-react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';

type Invoice = {
    filename: string;
    date: string;
    customer_name: string;
    total: number;
};

type Props = {
    invoices: Invoice[];
};

export default function Index({ invoices }: Props) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    return (
        <>
            <Head title="Invoices" />

            <div className="mx-auto w-full max-w-4xl p-4">
                <Heading
                    variant="small"
                    title="Invoices"
                    description="All previously generated invoices"
                />

                {invoices.length === 0 ? (
                    <p className="mt-6 text-sm text-muted-foreground">
                        No invoices yet. Create your first invoice to get started.
                    </p>
                ) : (
                    <div className="mt-6 overflow-x-auto">
                        <table className="w-full text-left text-sm">
                            <thead className="border-b text-muted-foreground">
                                <tr>
                                    <th className="pb-3 font-medium">Date</th>
                                    <th className="pb-3 font-medium">Customer</th>
                                    <th className="pb-3 text-right font-medium">Total</th>
                                    <th className="pb-3 text-right font-medium">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y">
                                {invoices.map((invoice) => (
                                    <tr key={invoice.filename}>
                                        <td className="py-3">{invoice.date}</td>
                                        <td className="py-3">{invoice.customer_name}</td>
                                        <td className="py-3 text-right">{formatCurrency(invoice.total)}</td>
                                        <td className="py-3 text-right">
                                            <div className="flex justify-end gap-1">
                                                <Button variant="ghost" size="sm" asChild>
                                                    <a
                                                        href={`/invoices/${invoice.filename}/view`}
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                    >
                                                        <Eye /> View
                                                    </a>
                                                </Button>
                                                <Button variant="ghost" size="sm" asChild>
                                                    <a
                                                        href={`/invoices/${invoice.filename}/download`}
                                                    >
                                                        <Download /> Download
                                                    </a>
                                                </Button>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                )}
            </div>
        </>
    );
}

Index.layout = {
    breadcrumbs: [
        {
            title: 'Invoices',
            href: '/invoices',
        },
    ],
};
