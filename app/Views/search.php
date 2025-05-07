<form class="needs-validation" novalidate="" action="/search_result" method="post" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
    <div class="row g-3"> 
        <div class="col-sm-6"> 
            <label for="type" class="form-label">Type</label> 
            <select class="form-select" id="type" name="type"> 
                <option value="">Choose...</option> 
                <option>Lux</option> 
                <option>Family</option> 
                <option>Standart double</option> 
                <option>Econom</option> 
                <option>President</option> 
            </select> 
            <div class="invalid-feedback">
                Please select a valid type.
            </div> 
        </div> 
        <div class="col-sm-6"> 
            <label for="peoples" class="form-label">Peoples</label> 
            <select class="form-select" id="peoples" name="peoples"> 
                <option value="">Choose...</option> 
                <option>1</option> 
                <option>2</option> 
                <option>3</option> 
                <option>4</option> 
                <option>5</option> 
            </select> 
            <div class="invalid-feedback">
                Please select a valid type.
            </div> 
        </div> 
        <div class="col-sm-6"> 
            <label for="rooms" class="form-label">Rooms</label> 
            <select class="form-select" id="rooms" name="rooms"> 
                <option value="">Choose...</option> 
                <option>1</option> 
                <option>2</option> 
                <option>3</option> 
                <option>4</option> 
                <option>5</option> 
            </select> 
            <div class="invalid-feedback">
                Please select a valid type.
            </div> 
        </div> 
        <div class="col-sm-6"> 
            <label for="bed" class="form-label">Beds</label> 
            <select class="form-select" id="bed" name="bed"> 
                <option value="">Choose...</option> 
                <option>single</option> 
                <option>double</option> 
                <option>1 double&other single</option> 
                <option>2 double</option>
            </select> 
            <div class="invalid-feedback">
                Please select a valid type.
            </div> 
        </div> 
        <div class="col-sm-6"> 
            <label for="price" class="form-label">Minimal price</label> 
            <input type="number" class="form-control" id="price" name="price" placeholder="Price"> 
            <div class="invalid-feedback">
                Please enter a valid price.
            </div>
        </div>
        <div class="col-sm-6"> 
            <label for="price" class="form-label">Minimal price</label> 
            <input type="number" class="form-control" id="price" name="price" placeholder="Price"> 
            <div class="invalid-feedback">
                Please enter a valid price.
            </div>  
        </div>  
    </div>
    <hr class="my-4">
    <button type="submit" class="w-100 btn btn-primary btn-lg">Find</button>
</form>
