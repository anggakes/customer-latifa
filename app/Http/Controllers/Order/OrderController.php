<?php

namespace App\Http\Controllers\Order;

use App\Models\RatingAndReview;
use App\Repository\Cart\CartInterface;
use App\Repository\Order\Data\Location;
use App\Repository\Order\OrderInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderController extends Controller
{
    //

    public function checkout(CartInterface $cart, OrderInterface $order){

        $userId     = request()->user()->id;
        $tnc        = request('tnc');
        $note       = request('note');
        $schedule   = request('schedule');

        if(!$tnc) throw new BadRequestHttpException('Anda harus menyetujui Syarat dan Ketentuan untuk melakukan Order');

        $cart->setSchedule($userId, $schedule);

        $cart->setNote($userId, $note);

        $additional = [];

        $order = $order->createOrder($userId, $cart, $additional);

        return response()->json($order);
    }

    public function allOrder(OrderInterface $order){


        $limit  = request('limit') ?: 10;
        $page   = request('page') ?: 1;
        $offset = ($page -1) * $limit;

        $data = $order->all(request()->user()->id, $limit, $offset, []);

        $transData = [];
        foreach ($data as $inv){
            $transData[] = [
                'invoice_number' => $inv->invoice_number,
                'total'          => $inv->total,
                'status'         => $inv->status->id,
            ];
        }

        $response = [
            'order' => $transData,
        ];
        return $response ;
    }


    public function detail(OrderInterface $order){
        $invoiceNumber = request('invoice_number');
        return response()->json($order->getOrder($invoiceNumber));
    }


    public function finished(OrderInterface $order){

        $invoiceNumber = request('invoice_number');

        $order->updateStatus($invoiceNumber, [], 'SERVICES_FINISHED');

        return response()->json($order->getOrder($invoiceNumber));

    }

    public function ratingAndReview(){
        
        $invoiceNumber = request('invoice_number');
        $rating = request('rating');
        $review = request('review');
        $userId = request()->user()->id;

        $reviewRating = new RatingAndReview([
            'invoice_number' => $invoiceNumber,
            'rating' => $rating,
            'review' => $review,
            'user_id' => $userId
        ]);


        return $reviewRating;
    }
}
