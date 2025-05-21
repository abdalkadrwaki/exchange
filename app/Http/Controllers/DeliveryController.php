<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{

    public function filter(Request $request)
    {

        $query = Delivery::query();

        // إذا لم يتم تحديد التاريخ، لا نقوم بجلب البيانات
        if (!$request->filled('from_date') || !$request->filled('to_date')) {
            return view('deliveries', ['deliveries' => collect()]);
        }

        // التحقق من وجود تاريخ البداية وإضافته للفلترة
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // التحقق من وجود تاريخ النهاية وإضافته للفلترة
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // فلترة العملة إذا كانت موجودة وليست فارغة
        if ($request->filled('currency') && $request->currency !== 'all') {
            $query->where('currency_name', $request->currency);
        }

        $deliveries = $query->with('user')->get();


        return view('deliveries', compact('deliveries'));
    }






    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);
        $userId = Auth::id();
        // 🔒 منع التعديل إلا إذا كان المستخدم هو المالك أو أدمن
        if ($delivery->user_id !== $userId && !auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالتعديل'], 403);
        }

        $delivery->update([
            'beneficiary' => $request->beneficiary,
            'transaction_type' => $request->transaction_type,
            'amount' => $request->amount,
            'currency_name' => $request->currency_name,
            'note' => $request->note,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);

        // فقط الأدمن يمكنه الحذف
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'غير مصرح بالحذف');
        }

        $delivery->delete();

        return redirect()->back()->with('success', 'تم الحذف بنجاح');
    }
}
