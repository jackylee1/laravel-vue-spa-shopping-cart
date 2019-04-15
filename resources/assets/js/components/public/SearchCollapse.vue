<template>
    <div class="collapse searchcollapse" id="collapseSubmenu">
        <div class="card card-body">
            <form class="form-inline d-flex justify-content-center">
                <input class="form-control" type="search" placeholder="Введите поисковое слово" aria-label="Поиск">
                <button class="btn">Поиск</button>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SearchCollapse'
    }
</script>