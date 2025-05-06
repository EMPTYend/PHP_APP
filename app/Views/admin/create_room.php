<div class="container" style="margin: 0 10%;"> 
    <div class="py-5 text-center"> 
        <h2>Create a new room</h2> 
        <p class="lead">Please fill in the details below to create a new room.</p> 
    </div> 
    <div class="col-lg-6 col-md-8 mx-auto"> 
        <div class="alert alert-info" role="alert"> 
            <strong>Note:</strong> All fields are required unless specified as optional. 
        </div> 
    </div> 
    <form class="needs-validation" novalidate="" action="/admin/create_room" method="post" enctype="multipart/form-data"> 
        <div class="row g-3"> 
            <div class="col-sm-6"> 
                <label for="firstName" class="form-label">Type of room</label> 
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="" value="" required=""> 
                <div class="invalid-feedback">
                    Valid type is required.
                </div> 
            </div> 
            <div class="col-sm-6"> 
                <label for="lastName" class="form-label">Last name</label> 
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="" value="" required=""> 
                <div class="invalid-feedback">
                    Valid last name is required.
                </div> 
            </div> 
            <div class="col-12"> 
                <label for="username" class="form-label">Username</label> 
                <div class="input-group has-validation"> 
                    <span class="input-group-text">@</span> 
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required=""> 
                    <div class="invalid-feedback">
                        Your username is required.
                    </div> 
                </div> 
            </div> 
            <div class="col-12"> 
                <label for="email" class="form-label">Email <span class="text-body-secondary">(Optional)</span></label> 
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com"> 
                <div class="invalid-feedback">
                    Please enter a valid email address for shipping updates.
                </div> 
            </div> 
            <div class="col-12"> 
                <label for="address" class="form-label">Address</label> 
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required=""> 
                <div class="invalid-feedback">
                    Please enter your shipping address.
                </div> 
            </div> 
            <div class="col-12"> 
                <label for="address2" class="form-label">Address 2 <span class="text-body-secondary">(Optional)</span></label> 
                <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite"> 
            </div> 
            <div class="col-md-5"> 
                <label for="country" class="form-label">Country</label> 
                <select class="form-select" id="country" name="country" required=""> 
                    <option value="">Choose...</option> 
                    <option>United States</option> 
                </select> 
                <div class="invalid-feedback">
                    Please select a valid country.
                </div> 
            </div> 
            <div class="col-md-4"> 
                <label for="state" class="form-label">State</label> 
                <select class="form-select" id="state" name="state" required=""> 
                    <option value="">Choose...</option> 
                    <option>California</option> 
                </select> 
                <div class="invalid-feedback">
                    Please provide a valid state.
                </div> 
            </div> 
            <div class="col-md-3"> 
                <label for="zip" class="form-label">Zip</label> 
                <input type="text" class="form-control" id="zip" name="zip" placeholder="" required=""> 
                <div class="invalid-feedback">
                    Zip code required.
                </div> 
            </div> 
        </div> 
        <hr class="my-4"> 
        <div class="form-check"> 
            <input type="checkbox" class="form-check-input" id="same-address" name="sameAddress"> 
            <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label> 
        </div> 
        <div class="form-check"> 
            <input type="checkbox" class="form-check-input" id="save-info" name="saveInfo"> 
            <label class="form-check-label" for="save-info">Save this information for next time</label> 
        </div> 
        <hr class="my-4"> 
        <h4 class="mb-3">Payment</h4> 
        <div class="my-3"> 
            <div class="form-check"> 
                <input id="credit" name="paymentMethod" type="radio" class="form-check-input" value="credit" checked="" required=""> 
                <label class="form-check-label" for="credit">Credit card</label> 
            </div> 
            <div class="form-check"> 
                <input id="debit" name="paymentMethod" type="radio" class="form-check-input" value="debit" required=""> 
                <label class="form-check-label" for="debit">Debit card</label> 
            </div> 
            <div class="form-check"> 
                <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" value="paypal" required=""> 
                <label class="form-check-label" for="paypal">PayPal</label> 
            </div> 
        </div> 
        <div class="row gy-3"> 
            <div class="col-md-6"> 
                <label for="cc-name" class="form-label">Name on card</label> 
                <input type="text" class="form-control" id="cc-name" name="ccName" placeholder="" required=""> 
                <small class="text-body-secondary">Full name as displayed on card</small> 
                <div class="invalid-feedback">
                    Name on card is required
                </div> 
            </div> 
            <div class="col-md-6"> 
                <label for="cc-number" class="form-label">Credit card number</label> 
                <input type="text" class="form-control" id="cc-number" name="ccNumber" placeholder="" required=""> 
                <div class="invalid-feedback">
                    Credit card number is required
                </div> 
            </div> 
            <div class="col-md-3"> 
                <label for="cc-expiration" class="form-label">Expiration</label> 
                <input type="text" class="form-control" id="cc-expiration" name="ccExpiration" placeholder="" required=""> 
                <div class="invalid-feedback">
                    Expiration date required
                </div> 
            </div> 
            <div class="col-md-3"> 
                <label for="cc-cvv" class="form-label">CVV</label> 
                <input type="text" class="form-control" id="cc-cvv" name="ccCVV" placeholder="" required=""> 
                <div class="invalid-feedback">
                    Security code required
                </div> 
            </div> 
        </div> 
        <hr class="my-4"> 
        <button type="submit" class="w-100 btn btn-primary btn-lg">Continue to checkout</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybB3Q1z5+6