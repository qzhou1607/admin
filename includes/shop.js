function AppViewModel() {
    var self = this;
    self.orders_array = ko.observableArray();
    self.remove_item = function() {
        self.orders_array.remove(this);
    }
}

function Order() {
        var self = this;
            self.order_id = ko.observable();
            self.product_id = ko.observable();
            self.customer_id = ko.observable();
            self.quantity = ko.observable();
            self.paid = ko.observable();
            self.product_name = ko.observable();
            self.product_price = ko.observable();
            self.product_inventory = ko.observable();
            self.product_img_url = ko.observable();
            self.total = ko.computed(function() {
                return (self.product_price()*self.quantity()).toFixed(2);
            });
        
}

data = new AppViewModel();

$.getScript('includes/get_orders.php');
