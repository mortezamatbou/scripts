<?php

class Product
{
    private string $title;
    private int $quantity;
    private float $price;

    function __construct(string $title, int $quantity, float $price)
    {
        $this->title = $title;
        $this->quantity = $quantity;
        $this->price = $price;
    }

    public function get_title(): string
    {
        return $this->title;
    }

    public function get_quantity(): int
    {
        return $this->quantity;
    }

    public function get_price(): float
    {
        return $this->price;
    }

}


class Basket
{

    /** @var array<Product> */
    private array $products = [];

    public function add_product($title, $quantity, $price): Basket
    {
        $this->products[] = new Product($title, $quantity, $price);
        return $this;
    }

    /**
     * @return Product[]
     */
    public function get_products(): array
    {
        return $this->products;
    }

    public function get_total_price(): float
    {
        $total_price = 0;

        if (!$this->products) {
            return $total_price;
        }

        foreach ($this->products as $product) {
            $total_price += $product->get_price() * $product->get_quantity();
        }

        return $total_price;
    }

}


class BasketView
{

    /**
     * @param $products Product[]
     */
    public function html(array $products)
    {
       foreach ($products as $product) {
            echo $product->get_title() . ' ' . $product->get_quantity() . ' ' . number_format($product->get_price()) . ' - ' . ($product->get_price() * $product->get_quantity()) . "\n";
       }
    }

}

class BasketHandler
{

    private Basket $basket;
    private BasketView $basket_view;

    function set_basket(Basket $basket)
    {
        $this->basket = $basket;
        $this->basket_view = new BasketView();
    }

    public function save()
    {
        echo "Save Basket in database.\n";
        return $this;
    }

    public function delete()
    {
        echo "Close basket and delete products.\n";
        return $this;
    }


    public function show_basket()
    {
        $this->basket_view->html($this->basket->get_products());
    }

}

$basket = new Basket();
$basket->add_product('Product1', 2, 1000)->add_product('Product2', 3, 1500)->add_product('Product3', 5, 4105);
echo $basket->get_total_price() . "\n";

$basket_handler = new BasketHandler();
$basket_handler->set_basket($basket);
$basket_handler->show_basket();