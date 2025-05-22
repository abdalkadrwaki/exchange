<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeController extends Controller
{
    public function filter(Request $request)
    {
        $currencies = [
            'USD' => 'دولار أمريكي',
            'EUR' => 'يورو',
            'SAR' => 'ريال سعودي',
            'EGP' => 'جنيه مصري',
            // أضف باقي العملات التي تريدها هنا
        ];

        $query = Exchange::query();

        if (!$request->filled('from_date') || !$request->filled('to_date')) {
            return view('exchanges', [
                'exchanges' => collect(),
                'currencies' => $currencies,
            ]);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('currency') && $request->currency !== 'all') {
            $query->where('currency_name', $request->currency);
        }

        $exchanges = $query->with('user')->get();

        return view('exchanges', [
            'exchanges' => $exchanges,
            'currencies' => $currencies,
        ]);
    }
    public function destroy($id)
    {
        $exchange = Exchange::findOrFail($id);

        // السماح فقط للأدمن بالحذف
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالحذف'], 403);
        }

        $exchange->delete();

        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'rate' => 'required|numeric',
            'currency_name' => 'required|string',
            'currency_name3' => 'required|string',
            'total' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $exchange = Exchange::findOrFail($id);
        $userId = Auth::id();
        // 🔒 منع التعديل إلا إذا كان المستخدم هو المالك أو أدمن
        if ($exchange->user_id !== $userId && !auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالتعديل'], 403);
        }
        $exchange->update([
            'amount' => $request->amount,
            'rate' => $request->rate,
            'note' => $request->note ?? '', // إذا كانت null، اجعلها ''
            'total' => $request->total,
            'currency_name' => $request->currency_name,
            'currency_name3' => $request->currency_name3,
        ]);

        return response()->json(['status' => 'success']);
    }
}
