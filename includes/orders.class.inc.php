<?php

class Order {
    private $id = 0;
    private $payment = '';
    private $phone = '';
    private $name = '';
    private $address = '';
    private $comment = '';
    private $status = 0;
    private $amount = 0;
    private $odd_money = 0;
    private $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPayment(): string
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getOddMoney(): int
    {
        return $this->odd_money;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function SetData($data) {
        $this->id = $data->id;
        $this->address = $data->address;
        $this->payment = $data->payment;
        $this->phone = $data->phone;
        $this->name = $data->name;
        $this->status = $data->status;
        $this->amount = $data->amount;
        $this->comment = $data->comment;
        $this->odd_money = $data->odd_money;
        $this->date = $data->date;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Orders {
    /** @var PDO **/
    private $PDO;

    public function __construct($PDO) {
        $this->PDO = $PDO;
    }

    public function UpdateOrder($order_id, $fields) {
        $sql = "UPDATE `Orders` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';
        $sql = $sql."WHERE `id`='".$order_id."' LIMIT 1";

        $this->PDO->query($sql);
    }

    public function AddOrder($fields, $products) {
        $sql = "INSERT INTO `Orders` SET ";
        foreach($fields as $k => $v)
            $sql = $sql."`$k` = '$v', ";
        $sql[strlen($sql)-2] = ' ';

        $this->PDO->query($sql);
        $order_id = $this->PDO->lastInsertId();

        $sql = 'INSERT INTO `Order_products` (`product_id`, `attribute_id`, `order_id`, `count`) VALUES ';
        $products_arr = [];
        foreach($products as $product)
            $products_arr[].= '('.$product['id'].', '.$product['attribute_id'].', '.$order_id.', '.$product['count'].')';
        if(sizeof($products_arr) > 0)
            $sql .= implode(',', $products_arr);

        $this->PDO->query($sql);

        return $order_id;
    }

    public function SetStatus($order_id, $status) {
        $fields = ['status' => $status];
        $this->UpdateOrder($order_id, $fields);
    }

    public function SelectOrders() {
        $query = $this->PDO->query("SELECT * FROM `Orders` ORDER BY `id` DESC")->fetchAll(PDO::FETCH_OBJ);
        $orders = [];
        foreach($query as $data) {
            $tmp_order = new Order();
            $tmp_order->SetData($data);
            $orders[] = $tmp_order;
        }
        return $orders;
    }
    public function SelectOrdersByStatus($status) {
        $query = $this->PDO->query("SELECT * FROM `Orders` WHERE `status` = '$status' ORDER BY `id` DESC")->fetchAll(PDO::FETCH_OBJ);
        $orders = [];
        foreach($query as $data) {
            $tmp_order = new Order();
            $tmp_order->SetData($data);
            $orders[] = $tmp_order;
        }
        return $orders;
    }
    public function SelectUnnoticedOrders() {
        $orders = $this->PDO->query("SELECT * FROM `Orders` WHERE `noticed` = 0 ORDER BY `id` ASC")->fetchAll(PDO::FETCH_OBJ);
        return $orders;
    }
    public function UpdateOrdersNoticed($orders_list) {
        $this->PDO->query("UPDATE `Orders` SET `noticed` = 1 WHERE `id` IN (".implode(',', $orders_list).")");
    }

    public function SelectOrder($order_id) {
        $query = $this->PDO->query("SELECT * FROM `Orders` WHERE `id` = $order_id");
        if($query) {
            $order = new Order();
            $order->SetData($query->fetch(PDO::FETCH_OBJ));
            return $order;
        }
        return false;
    }
    public function SelectOrderProducts($order_id) {
        $query = $this->PDO->query("SELECT * FROM `Order_products` WHERE `order_id` = $order_id");
        if($query)
            return $query->fetchAll(PDO::FETCH_OBJ);
        return false;
    }

    public function SendOrder($order_id) {
        include BASEDIR.'/vendor/autoload.php';
        include_once BASEDIR.'/includes/products.class.inc.php';

        $Products = new Products($this->PDO);
        $order = $this->SelectOrder($order_id);

        $mail = new PHPMailer(true);

        $body = ' 
        <style>
            .info-line {
                margin-bottom: 10px;
            }
            .admin-table {
                width: 100%;
                border-collapse: collapse;
            }
            .admin-table td,
            .admin-table th {
                padding: 10px;
                border: 1px solid #4f4f4f;
            }
            a {
                color: #000000;
                text-decoration: none;
            }
        </style>
        <div class="info-line"><b>Метод оплаты:</b> '.Orders::GetPaymentName($order->getPayment()).'</div>
        <div class="info-line"><b>Имя клиента:</b> '.$order->getName().'</div>
        <div class="info-line"><b>Телефон:</b> '.$order->getPhone().'</div>
        <div class="info-line"><b>Адрес доставки:</b> '.$order->getAddress().'</div>
        <div class="info-line"><b>Сумма:</b> '.$order->getAmount().'р.</div>
        <div class="info-line"><b>Комментарий:</b> <pre>'.$order->getComment().'</pre></div>';
        $body .= ($order->getOddMoney() > 0)?('<div class="info-line"><b>Сдача с:</b> '.$order->getOddMoney().'р. ('.($order->getOddMoney() - $order->getAmount()).'р.)</div>'):'';
        $body .= '
        <table class="admin-table" style="max-width: 700px; margin-top: 40px;">
            <tr>
                <th>Товар</th>
                <th>Вариант</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <th>Сумма</th>
            </tr>';
            $order_products = $this->SelectOrderProducts($order_id);
            foreach($order_products as $order_product) {
                $product = $Products->SelectProduct($order_product->product_id);
                $attribute = $product->GetAttributeById($order_product->attribute_id);
                $body .= '<tr>';
                    $body .= '<td><a href="'.Config::GetBaseUrl().'/admin/product.php?product_id='.$product->getId().'">'.$product->getTitle().'</a></td>';
                    $body .= '<td>'.$attribute->name.'</td>';
                    $body .= '<td>'.$order_product->count.'</td>';
                    $body .= '<td>'.$attribute->price.'</td>';
                    $body .= '<td>'.$attribute->price * $order_product->count.'</td>';
                $body .= '</tr>';
            }
        $body .= '</table>';

        try {
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.timeweb.ru';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'admin_order@noy48.ru';                     //SMTP username
            $mail->Password   = '8pC3mpS6';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you

            $mail->setFrom('admin_order@noy48.ru', 'Заказы НОЙ');
            $mail->addAddress(Config::GetSettings('admin_email'));
            $mail->CharSet = 'UTF-8';

            $mail->isHTML(true);
            $mail->Subject = 'Новый заказ №'.$order_id;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public static function GetPaymentName($payment) {
        $payments = [
            'cash' => 'Наличные',
            'card' => 'Карта',
        ];
        return $payments[$payment] ?? false;
    }
    public static function GetStatusName($status) {
        $statuses = [
            1 => 'Новый',
            2 => 'Завершен',
            3 => 'Отменен',
        ];
        return $statuses[$status] ?? false;
    }
    public static function GetStatusColor($status) {
        $statuses = [
            1 => 'orange',
            2 => 'green',
            3 => 'red',
        ];
        return $statuses[$status] ?? false;
    }
}