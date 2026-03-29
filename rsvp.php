<?php
include 'includes/header.php';
?>

<style>
.person-block {
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.diet-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 12px;
}

.diet-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.diet-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
}

.other-text {
    margin-left: 28px;
    margin-top: -5px;
    padding: 8px;
    width: 60%;
}

.add-person-btn {
    margin-top: 20px;
    background: rgba(255,255,255,0.2);
    padding: 10px 18px;
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.3);
    cursor: pointer;
    color: var(--text-main);
    transition: 0.3s;
}

.add-person-btn:hover {
    background: rgba(255,255,255,0.35);
}

.submit-btn {
    margin-top: 30px;
    background: var(--accent);
    color: #000;
    padding: 12px 22px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
}

.submit-btn:hover {
    background: #ffe2b3;
}

.success-message {
    margin-top: 25px;
    font-size: 1.3rem;
    color: var(--accent);
    text-align: center;
}
</style>

<div class="section-card" id="rsvp-container">

    <h2>Potwierdzenie obecności</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message" style="text-align:center; margin-top:40px;">
        Dziękujemy za potwierdzenie obecności!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['exists'])): ?>
        <div class="success-message" style="color:#ff8080;">
            Ta osoba już potwierdziła swoją obecność!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['duplikaty'])): ?>
        <div class="success-message" style="text-align:center; margin-top:40px; color:#ff8080;">
        Osoby, które już potwierdziły obecność:<br>
        <strong><?= htmlspecialchars($_GET['duplikaty']) ?></strong>
        </div>
    <?php endif; ?>

    <?php
    $success = isset($_GET['success']);
    $duplikaty = isset($_GET['duplikaty']);
    ?>

    <?php if (!$success && !$duplikaty): ?>

    <form action="rsvp_submit.php" method="POST" id="rsvp-form">

        <div id="persons-wrapper">

            <!-- OSOBA 1 -->
            <div class="person-block">

                <div class="form-row">
                    <label>Imię:</label>
                    <input type="text" name="imie[]" required>
                </div>

                <div class="form-row">
                    <label>Nazwisko:</label>
                    <input type="text" name="nazwisko[]" required>
                </div>

                <div class="form-row">
                    <label>Czy potwierdzasz swoją obecność?</label>
                    <select name="obecnosc[]" class="presence-select" required>
                        <option value="">-- Wybierz --</option>
                        <option value="tak">Tak</option>
                        <option value="nie">Nie</option>
                    </select>
                </div>

                <!-- DIETA – POKAZUJE SIĘ TYLKO JEŚLI "TAK" -->
                <div class="diet-section" style="display:none;">

                    <label>Wymagania co do diety:</label>

                    <div class="diet-options">

                        <label class="diet-item">
                            <input type="checkbox" name="dieta_gluten[0]" value="bezglutenowa">
                            <span>Bezglutenowa</span>
                        </label>

                        <label class="diet-item">
                            <input type="checkbox" name="dieta_vege[0]" value="wegetariańska">
                            <span>Wegetariańska</span>
                        </label>

                        <label class="diet-item">
                            <input type="checkbox" name="dieta_vegan[0]" value="wegańska">
                            <span>Wegańska</span>
                        </label>

                        <label class="diet-item">
                            <input type="checkbox" name="dieta_lactose[0]" value="bez laktozy">
                            <span>Bez laktozy</span>
                        </label>

                        <label class="diet-item">
                            <input type="checkbox" class="other-check">
                            <span>Inne:</span>
                        </label>

                        <input type="text" name="dieta_other_text[0]" class="other-text" placeholder="Wpisz inne wymagania" style="display:none;">

                    </div>

                </div>

            </div>

        </div>

        <!-- DODAJ KOLEJNĄ OSOBĘ -->
        <button type="button" id="add-person" class="add-person-btn">+ Potwierdź obecność kolejnej osoby</button>

        <!-- WYŚLIJ -->
        <button type="submit" class="submit-btn">Wyślij potwierdzenie</button>

    </form>

    <?php endif; ?>

</div>

<script>
document.addEventListener("change", function(e) {

    // POKAZYWANIE DIETY
    if (e.target.classList.contains("presence-select")) {
        const block = e.target.closest(".person-block");
        const diet = block.querySelector(".diet-section");

        diet.style.display = e.target.value === "tak" ? "block" : "none";
    }

    // INNE – pokazuje pole tekstowe
    if (e.target.classList.contains("other-check")) {
        const text = e.target.closest(".person-block").querySelector(".other-text");
        text.style.display = e.target.checked ? "block" : "none";
    }
});

// DODAWANIE OSÓB
document.getElementById("add-person").addEventListener("click", () => {
    const wrapper = document.getElementById("persons-wrapper");
    const first = wrapper.querySelector(".person-block");
    const clone = first.cloneNode(true);

    // reset wartości
    clone.querySelectorAll("input").forEach(i => {
        i.value = "";
        if (i.type === "checkbox") i.checked = false;
    });
    clone.querySelector("select").value = "";
    clone.querySelector(".diet-section").style.display = "none";
    clone.querySelector(".other-text").style.display = "none";

    wrapper.appendChild(clone);
});
</script>

<?php include 'includes/footer.php'; ?>
