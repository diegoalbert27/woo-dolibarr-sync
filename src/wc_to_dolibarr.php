<script>
	$(document).ready(function() {


		console.log('ready');

		const consumerKey = 'ck_6ed6964e61d963a2f6e9c8caad7ea7a8b9ace979&'
		const consumerSecret = 'cs_e2ee3264e1e6315030b2101716f72dbb736a2565'

		const base_uri_dolibarr = 'http://localhost/woo-dolibarr-sync/src';

		const base_uri_wc_products_post = `https://wp-site.com/index.php/wp-json/wc/v2/products?consumer_key=${consumerKey}&consumer_secret=${consumerSecret}`;
		
		const base_uri_wc_category_post = `https://wp-site.com/index.php/wp-json/wc/v2/categories?consumer_key=${consumerKey}&consumer_secret=${consumerSecret}`;

		const base_uri_wc_customers_get = `https://wp-site.com/index.php/wp-json/wc/v2/customers?consumer_key=${consumerKey}&consumer_secret=${consumerSecret}`;

		let products;
		let categories;

		$.get(`${base_uri_dolibarr}/getProducts.php`)
			.done(resp => {
				products = JSON.parse(resp)

				products.forEach(product => {

					let postData = {
						name: product.label,
						type: "simple",
						regular_price: product.price,
						description: product.description,
						short_description: product.description,
						sku: product.ref,
						categories: [],
						images: []
					}

					$.post(`${base_uri_wc_products_post}`, postData)
						.done(resp => {
							console.log(resp)
						})
						.fail((err) => console.log(err))
				})
			})
			.fail(err => console.log(err))

		$.get(`${base_uri_dolibarr}/getCategories.php`)
			.done(resp => {
				categories = JSON.parse(resp)

				categories.forEach(category => {

					let postData = {
						"name": category.label,
						"image": {}
					}

					$.post(`${base_uri_wc_products_post}`, postData)
						.done(resp => {
							console.log(resp)
						})
						.fail((err) => console.log(err))
				})
			})
			.fail(err => console.log(err))

		// $.get(`${base_uri_wc_customers_get}`)
		// 	.done(resp => {
		// 		customers = JSON.parse(resp)

		// 		customers.forEach(customer => {

		// 			console.log(customer)
		// 		})
		// 	})
		// 	.fail(err => console.log(err))
	});
</script>