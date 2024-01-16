<div class="container text-center font-text text-black">
    <div class="row-2 py-2">
        <h1 class="fw-bold">Cerca</h1>
    </div>
    <!-- search field -->
    <div class="row-md-2 py-2">
        <div class="input-group rounded" id="searchBar">
            <span class="input-group-text bg-primary border-0 cursor-pointer" id="searchIcon"><em class="fa-solid fa-magnifying-glass" aria-hidden="true"></em></span>
            <input type="text" class="form-control bg-primary shadow-sm" placeholder="Cerca" id="searchBarInput" title="testo per la ricerca di gnam" aria-label="testo per la ricerca di gnam" <?php if (isset($_GET["q"])) { echo 'value="' . $_GET["q"] . '" '; } ?>/>
    	</div>
    </div>
    <!-- ingredients -->
    <div class="row-md-2 py-2">
        <!-- Button con counter -->
        <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white" id="ingredientsButton">
            Ingredienti <span class="badge rounded-pill bg-accent" id="ingredientsCount">0</span>
        </button>
    </div>
</div>

<div class="d-none justify-content-center align-items-center mt-5" id="loaderDiv">
  <div class="loadingspinner"></div>
</div>

<div class="d-none container" id="searchResultsDiv">
    <!-- search results content -->
</div>

<script>
    let ingredients = [];
    let currentResult;

    const getIngredientHTML = (ingredient) => {
        return `<p class="text-black"><button type="button" class="btn btn-bounce bg-primary text-black" id="removeIngredient-${ingredient}">
                    <em class="fa-solid fa-trash-can" aria-hidden="true"></em></button>&nbsp${ingredient}</p>`;
    };

    const openIngredients = () => {
        let html = `<div class="row-md-2 py-2">
                        <div class="input-group rounded">
                            <span class="input-group-text bg-primary border-0 cursor-pointer" id="searchIngredientsIcon"><em class="fa-solid fa-magnifying-glass"></em></span>
                            <input type="text" id="ingredientInput" class="form-control bg-primary shadow-sm" placeholder="Cerca Ingredienti" title="testo per la ricerca di ingredienti" aria-label="testo per la ricerca di ingredienti" />
                        </div>
                    </div>
                    <hr />
                    <p id="noIngredientsText" class="d-none text-black">Non hai selezionato ingredienti.</p>
                    <div class="text-center" id="searchedIngredients">${ingredients.map(getIngredientHTML).join('')}</div>
                    <hr />
                    <div class="row m-0 p-0">
                        <div class="col-6">
                            <button type="button" class="btn btn-bounce rounded-pill bg-alert fw-bold text-white w-100" id="resetIngredients">Reset</button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="okButton" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white w-100">Ok</button>
                        </div>
                    </div>`;

        showSwal('Scegli ingredienti', html);
        if (ingredients.length == 0) {
            $("#noIngredientsText").removeClass("d-none");
        }
        ingredients.forEach(addHandlersToIngredient);

        $("#resetIngredients").on("click", function () {
            ingredients = [];
            $("#searchedIngredients").empty();
            $('#ingredientsCount').html(ingredients.length);
            $("#noIngredientsText").removeClass("d-none");
        });

        $('#searchIngredientsIcon').on("click", addIngredient);
        $('#ingredientInput').keypress(function(event) {
            if (event.which === 13) {
                addIngredient();
            }
        });

        $('#okButton').click(function() {
            closeSwal();
        });
    }

    const addIngredient = () => {
        let newIngredient = $('#ingredientInput').val().trim();
        if (!newIngredient || ingredients.includes(newIngredient)) {
            return;
        }
        if (ingredients.length == 0) {
            $("#noIngredientsText").addClass("d-none");
        }
        ingredients.push(newIngredient);
        addHandlersToIngredient(newIngredient);
        $("#searchedIngredients").append(getIngredientHTML(newIngredient));
        $('#ingredientInput').val('');
        $('#ingredientsCount').html(ingredients.length);
    }

    const addHandlersToIngredient = (ingredient) => {
        $("#removeIngredient-" + ingredient).on("click", function () {
            let indexToRemove = $(this).parent().index();
            ingredients.splice(indexToRemove, 1);
            $(this).parent().remove();
            $('#ingredientsCount').html(ingredients.length);
            if (ingredients.length == 0) {
                $("#noIngredientsText").removeClass("d-none");
            }
        });
    }

    const searchVideos = () => {
        let query = $('#searchBarInput').val().trim();

        $('#searchBarInput').val('');
        $('#searchResultsDiv').addClass('d-none');
        $('#loaderDiv').addClass('d-flex').removeClass('d-none');

        $.get("api/search.php", {
            query: query,
            api_key: "<?php echo $_SESSION['api_key']; ?>",
            ingredients: JSON.stringify(ingredients),
            action: "byQuery"
        }, (result) => {
            $('#loaderDiv').removeClass('d-flex').addClass('d-none');
            $('#searchResultsDiv').removeClass('d-none');
            $('#searchResultsDiv').html('');
            currentResult = JSON.parse(result);

            if (result === '[]') {
                $('#searchResultsDiv').html('<div class="fs-6 mt-4 text-center text-black">Nessuno gnam trovato.</div>');
                return;
            }

            let gnamPerRow = 3;
            let rowDiv = $('<div class="row my-3">');

            for (let o in currentResult) {
                let img = $(`<img class="img-grid col-4 btn-bounce cursor-pointer" onclick="setGnamsToWatchFrom(${currentResult[o].id}, currentResult)" alt="Copertina gnam" src="assets/gnams_thumbnails/${currentResult[o].id}.jpg" />`);
                rowDiv.append(img);
                gnamPerRow--;

                if (gnamPerRow === 0) {
                    $('#searchResultsDiv').append(rowDiv);
                    rowDiv = $('</div><div class="row my-3">');
                    gnamPerRow = 3;
                }
            }

            if (gnamPerRow !== 3) {
                $('#searchResultsDiv').append(rowDiv);
            }
        });
    }

    $(document).ready(function(){
        $('#searchBar').keypress(function(e) {
            if (e.which === 13){
                searchVideos();
            }
        });

        $("#ingredientsButton").on("click", openIngredients);
        $('#searchIcon').on("click", searchVideos);
    });
</script>