<div class="container text-center font-text">
    <div class="row-2 py-2 h4" id="headerDiv">
        <h1 class="fw-bold">Pubblica Gnam</h1>
    </div>

    <div class="row container overflow-auto p-0 m-0 align-content-center" id="pageContentDiv">
        <!-- video chooser field -->
        <div class="row-md px-4 h4">
            <h2 class="fw-bold">Scegli video</h2>
            <input type="file" class="form-control bg-primary rounded shadow-sm" />
        </div>
        <!-- thumbnail chooser field -->
        <div class="row-md px-4 h4">
            <h2 class="fw-bold">Scegli copertina</h2>
            <input type="file" class="form-control bg-primary rounded shadow-sm" />
        </div>
        <!-- description field -->
        <div class="row-md-6 px-4 h4">
            <h2 class="fw-bold">Descrizione</h2>
            <textarea class="form-control bg-primary rounded shadow-sm" rows="3"></textarea>
        </div>
        <!-- ingredients -->
        <div class="row-sm pt-2 pb-0 ">
            <!-- Button con counter -->
            <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white" id="ingredientsButton">
                Ingredienti <span class="badge rounded-pill bg-accent" id="ingredientsCount">0</span>
            </button>
        </div>
        <!-- tag -->
        <div class="row-sm pt-1 h-0">
            <!-- Button con counter -->
            <button type="button" class="btn btn-bounce rounded-pill bg-secondary fw-bold text-white" id="hashtagsButton" >
                Hashtag <span class="badge rounded-pill bg-accent" id="hashtagsCount">0</span>
            </button>
        </div>
        <!-- read all notification button -->
        <div class="row-md-4 pt-4">
            <a href="#" role="button" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white" id="publishBtn">Pubblica Gnam</a>
        </div>
    </div>
</div>

<script>
    let hashtags = [];
    let ingredients = [];

    const openIngredients = () => {
        let modalContent = '';

        if (ingredients.length > 0) {
            modalContent = ingredients.map(ingredient => `
                <div class="row m-0 p-0 align-items-center text-black">
                    <div class="col-3 m-0 p-1">
                        <p class="m-0 fs-7">${ingredient[0]}</p>
                    </div>
                    <div class="col-3 m-0 p-1"><select id="${ingredient[0]}Quantity" class="form-select bg-primary rounded shadow-sm fs-7 text-black">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                        </select></div>
                    <div class="col-4 m-0 p-1"><select id="${ingredient[0]}Scale" class="form-select bg-primary rounded shadow-sm fs-7 text-black">
                            <option>c.ino</option>
                            <option>gr.</option>
                            <option>qb</option>
                        </select></div>
                    <div class="col-2 m-0 p-1"><button type="button" class="btn btn-bounce bg-primary text-black fs-7"
                            onclick="removeIngredient(this)"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button></div>
                </div>
            `).join('');
        }

        let html = `
            <div class="row-md-2 py-2">
                <div class="input-group rounded">
                    <span class="input-group-text bg-primary border-0" id="searchIngredientIcon">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    </span>
                    <input type="text" class="form-control bg-primary shadow-sm" placeholder="Cerca Ingredienti" id="searchIngredients">
                </div>
            </div>
            <hr>
            <p id="noIngredientsText" class="d-none">Non hai selezionato ingredienti.</p>
            <div class="text-center" id="searchedIngredients">${modalContent}</div>
            <hr>
            <div class="row m-0 p-0">
                <div class="col-6">
                    <button type="button" class="btn btn-bounce rounded-pill bg-alert fw-bold text-white w-100" id="resetIngredients">Reset</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white w-100" id="okButtonIngredients">Ok</button>
                </div>
            </div>
        `;

        const modal = showSwal('Scegli Ingredienti', html);
        ingredients.forEach(ingredient => {
            $('[id="' + ingredient[0] + 'Quantity"]').val(ingredient[1]);
            $('[id="' + ingredient[0] + 'Scale"]').val(ingredient[2]);
            $('[id="' + ingredient[0] + 'Quantity"]').on("change", function() {
                ingredient[1] = $('[id="' + ingredient[0] + 'Quantity"]').val();
            });
            $('[id="' + ingredient[0] + 'Scale"]').on("change", function() {
                ingredient[2] = $('[id="' + ingredient[0] + 'Scale"]').val();
            });
        });

        if (ingredients.length == 0) {
            $("#noIngredientsText").removeClass("d-none");
        }
        $('#searchIngredientIcon').on("click", addIngredient);
        $('#resetIngredients').on("click", resetIngredients);
        $('#searchIngredients').keypress(function(event) {
            if (event.which === 13) {
                addIngredient();
            }
        });

        $('#okButtonIngredients').click(function() {
            closeSwal();
        });
    }

    const addIngredient = () => {
        let newIngredient = $('#searchIngredients').val().trim();
        if (!newIngredient || ingredients.includes(newIngredient)) {
            return;
        }
        $("#searchedIngredients").append(`
            <div class="row text-black m-0 p-0 align-items-center text-black">
                <div class="col-3 m-0 p-1">
                    <p class="m-0 fs-7">${newIngredient}</p>
                </div>
                <div class="col-3 m-0 p-1"><select id="${newIngredient}Quantity" class="form-select bg-primary rounded shadow-sm fs-7 text-black">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                    </select></div>
                <div class="col-4 m-0 p-1"><select id="${newIngredient}Scale" class="form-select bg-primary rounded shadow-sm fs-7 text-black">
                        <option>c.ino</option>
                        <option>gr.</option>
                        <option>qb</option>
                    </select></div>
                <div class="col-2 m-0 p-1"><button type="button" class="btn btn-bounce bg-primary text-black fs-7"
                        onclick="removeIngredient(this)"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button></div>
            </div>
        `);
        if (ingredients.length == 0) {
            $("#noIngredientsText").addClass("d-none");
        }
        ingredients.push([newIngredient, $('[id="' + newIngredient + 'Quantity"]').val(), $('[id="' + newIngredient + 'Scale"]').val()]);
        let newIngredientIndex = ingredients.length - 1;
        $('[id="' + newIngredient + 'Quantity"]').on("change", function() {
            ingredients[newIngredientIndex][1] = $('[id="' + newIngredient + 'Quantity"]').val();
        });
        $('[id="' + newIngredient + 'Scale"]').on("change", function() {
            ingredients[newIngredientIndex][2] = $('[id="' + newIngredient + 'Scale"]').val();
        });
        $('#searchIngredients').val('');
        $('#ingredientsCount').html(ingredients.length);
    }

    const removeIngredient = (element) => {
        const ingredientEntry = $(element).closest('.row');
        const ingredientName = ingredientEntry.find('p').text().trim();
        const indexToRemove = ingredients.indexOf(ingredientName);
        if (indexToRemove !== -1) {
            ingredients.splice(indexToRemove, 1);
            ingredientEntry.remove();
            $('#ingredientsCount').html(ingredients.length);
            if (ingredients.length == 0) {
                $("#noIngredientsText").removeClass("d-none");
            }
        }
    }

    const resetIngredients = () => {
        ingredients = [];
        $("#searchedIngredients").empty();
        $('#ingredientsCount').html(ingredients.length);
        $("#noIngredientsText").removeClass("d-none");
    }

    const openHashtags = () => {
        let modalContent = '';

        if (hashtags.length > 0) {
            modalContent = hashtags.map(hashtag => `
                <p class="text-black"><button type="button" class="btn btn-bounce bg-primary text-black" onclick="removeHashtag(this)">
                    <i class="fa-solid fa-trash-can"></i></button>&nbsp${hashtag}</p>
            `).join('');
        }

        let html = `<div class="row-md-2 py-2">
                        <div class="input-group rounded">
                            <span class="input-group-text bg-primary border-0" id="searchHashtagIcon"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="hashtagInput" class="form-control bg-primary shadow-sm" placeholder="Cerca Hashtag">
                        </div>
                    </div>
                    <hr>
                    <p id="noHashtagsText" class="d-none">Non hai selezionato hashtag.</p>
                    <div class="text-center" id="searchedHashtags">${modalContent}</div>
                    <hr>
                    <div class="row m-0 p-0">
                        <div class="col-6">
                            <button type="button" class="btn btn-bounce rounded-pill bg-alert fw-bold text-white w-100" id="resetHashtags">Reset</button>
                        </div>
                        <div class="col-6">
                            <button type="button" id="okButton" class="btn btn-bounce rounded-pill bg-accent fw-bold text-white w-100">Ok</button>
                        </div>
                    </div>`;

        const modal = showSwal('Scegli hashtag', html);

        if (hashtags.length == 0) {
            $("#noHashtagsText").removeClass("d-none");
        }
        $('#searchHashtagIcon').on("click", addHashtag);
        $('#resetHashtags').on("click", resetHashtags);
        $('#hashtagInput').keypress(function(event) {
            if (event.which === 13) {
                addHashtag();
            }
        });

        $('#okButton').click(function() {
            closeSwal();
        });
    }

    const addHashtag = () => {
        let newHashtag = $('#hashtagInput').val().trim();
        while(newHashtag.startsWith('#')) {
            newHashtag = newHashtag.slice(1);
        }
        if(!newHashtag) {
            return
        }
        newHashtag = '#' + newHashtag;
        if (hashtags.includes(newHashtag)) {
            return;
        }
        if (hashtags.length == 0) {
            $("#noHashtagsText").addClass("d-none");
        }
        hashtags.push(newHashtag);
        $("#searchedHashtags").append(`
            <p class="text-black"><button type="button" class="btn btn-bounce bg-primary text-black" onclick="removeHashtag(this)">
                <i class="fa-solid fa-trash-can"></i></button>&nbsp${newHashtag}</p>
        `);
        $('#hashtagInput').val('');
        $('#hashtagsCount').html(hashtags.length);
    }

    const removeHashtag = (element) => {
        let indexToRemove = $(element).parent().index();
        hashtags.splice(indexToRemove, 1);
        $(element).parent().remove();
        $('#hashtagsCount').html(hashtags.length);
        if (hashtags.length == 0) {
            $("#noHashtagsText").removeClass("d-none");
        }
    }

    const resetHashtags = () => {
        hashtags = [];
        $("#searchedHashtags").empty();
        $('#hashtagsCount').html(hashtags.length);
        $("#noHashtagsText").removeClass("d-none");
    }

    const publish = () => {
        // TO DO: Handling dati con PHP

        let html = `<div class="row-md-2 py-2 text-center text-black"><i class="fa-solid fa-check fa-2xl"></i></div>`;
        showSwalSmall('Gnam pubblicato', html);
    }

    $("#publishBtn").on("click", publish);
    $("#hashtagsButton").on("click", openHashtags);
    $("#ingredientsButton").on("click", openIngredients);
</script>