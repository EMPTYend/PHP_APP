<div class="container my-4">
    <!-- Вывод ошибок -->
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form class="needs-validation" novalidate="" action="/admin/rooms" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

        <div class="row g-3"> 
            <div class="col-sm-6"> 
                <label for="type" class="form-label">Type</label> 
                <select class="form-select" id="type" name="type" required> 
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
                <select class="form-select" id="peoples" name="peoples" required> 
                    <option value="">Choose...</option> 
                    <option>1</option> 
                    <option>2</option> 
                    <option>3</option> 
                    <option>4</option> 
                    <option>5</option> 
                </select> 
                <div class="invalid-feedback">
                    Please select a valid option.
                </div> 
            </div> 

            <div class="col-sm-6"> 
                <label for="rooms" class="form-label">Rooms</label> 
                <select class="form-select" id="rooms" name="rooms" required> 
                    <option value="">Choose...</option> 
                    <option>1</option> 
                    <option>2</option> 
                    <option>3</option> 
                    <option>4</option> 
                    <option>5</option> 
                </select> 
                <div class="invalid-feedback">
                    Please select a valid option.
                </div> 
            </div> 

            <div class="col-sm-6"> 
                <label for="bed" class="form-label">Beds</label> 
                <select class="form-select" id="bed" name="bed" required> 
                    <option value="">Choose...</option> 
                    <option>single</option> 
                    <option>double</option> 
                    <option>1 double & other single</option> 
                    <option>2 double</option>
                </select> 
                <div class="invalid-feedback">
                    Please select a valid option.
                </div> 
            </div> 
            
            <div class="col-12"> 
                <label for="price" class="form-label">Price</label> 
                <input type="number" class="form-control" id="price" name="price" placeholder="US" required> 
                <div class="invalid-feedback">
                    Please enter a price.
                </div> 
            </div> 

            <div class="col-12"> 
                <label for="description" class="form-label">Description</label> 
                <textarea class="form-control" id="description" name="description" required></textarea> 
                <div class="invalid-feedback">
                    Please enter a description.
                </div>
            </div>

            <div class="col-12">
                <label for="fileElem" class="form-label">Upload Images</label>
                <div id="drop-area" class="border border-2 rounded p-4 text-center text-muted" style="border-style: dashed;">
                    <p>Drag & drop images here or click to select</p>
                    <input type="file" id="fileElem" name="images[]" multiple accept="image/*" style="display:none" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileElem').click();">Choose Files</button>
                    <div id="preview" class="mt-3 d-flex flex-wrap gap-2"></div>
                    <div class="invalid-feedback">
                        Please upload at least one image.
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="w-100 btn btn-primary btn-lg">Submit</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);

        const dropArea = document.getElementById("drop-area");
        const fileInput = document.getElementById("fileElem");
        const preview = document.getElementById("preview");

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        dropArea.addEventListener('dragover', () => dropArea.classList.add('bg-light'));
        dropArea.addEventListener('dragleave', () => dropArea.classList.remove('bg-light'));
        dropArea.addEventListener('drop', handleDrop);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles(files);
        }

        fileInput.addEventListener('change', () => handleFiles(fileInput.files));

        function handleFiles(files) {
            preview.innerHTML = '';
            [...files].forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.style.maxWidth = "120px";
                        img.classList.add("rounded", "shadow-sm", "me-2", "mb-2");
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
