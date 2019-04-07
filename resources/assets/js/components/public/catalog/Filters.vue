<template>
    <div>
        <div class="row filter_wrapper">
            <div class="col-md-2">
                <select class="form-control-sm custom-select">
                    <option selected>БРЕНД</option>
                    <option value="1">Abercrombie&Fitch</option>
                    <option value="2">NPC</option>
                    <option value="3">Roots Of Fight</option>
                    <option value="4">Nike</option>
                    <option value="5">Power System</option>
                    <option value="6">RYU</option>
                    <option value="7">Under Armour</option>
                </select>
            </div>
            <div class="col-md-1">
                <select class="form-control-sm custom-select">
                    <option selected>РАЗМЕР</option>
                    <option value="1">XS</option>
                    <option value="2">S</option>
                    <option value="3">M</option>
                    <option value="4">L</option>
                    <option value="5">XL</option>
                    <option value="6">XXL</option>
                    <option value="7">XXXL</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control-sm custom-select">
                    <option selected>ТИП ПОШИВА</option>
                    <option value="1">A</option>
                    <option value="2">B</option>
                    <option value="3">C</option>
                    <option value="4">D</option>
                    <option value="5">E</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control-sm custom-select">
                    <option selected>ПЛОТНОСТЬ</option>
                    <option value="1">A</option>
                    <option value="2">B</option>
                    <option value="3">C</option>
                    <option value="4">D</option>
                    <option value="5">E</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control-sm custom-select">
                    <option selected>МАТЕРИАЛ</option>
                    <option value="1">A</option>
                    <option value="2">B</option>
                    <option value="3">C</option>
                    <option value="4">D</option>
                    <option value="5">E</option>
                </select>
            </div>
            <div class="col-md-1">
                <select class="form-control-sm custom-select">
                    <option selected>ЦВЕТ</option>
                    <option value="1">A</option>
                    <option value="2">B</option>
                    <option value="3">C</option>
                    <option value="4">D</option>
                    <option value="5">E</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control-sm custom-select">
                    <option selected>ПРИМЕНЕНИЕ</option>
                    <option value="1">A</option>
                    <option value="2">B</option>
                    <option value="3">C</option>
                    <option value="4">D</option>
                    <option value="5">E</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Filters'
    }
</script>