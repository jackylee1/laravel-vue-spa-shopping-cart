<template>
    <div class="row description_text">
        <div class="col-md-7">
            <p v-html="product.description"></p>
        </div>
        <div class="col-md-5 size_table" id="size_table">
            <h5>Таблица размеров</h5>
            <table class="table size_table_title">
                <thead class="thead-light">
                <tr class="table_head">
                    <th scope="col">Размер</th>
                    <th scope="col">Грудь</th>
                    <th scope="col">Талия</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">S</th>
                    <td>90-94</td>
                    <td>78-82</td>
                </tr>
                <tr>
                    <th scope="row">M</th>
                    <td>94-98</td>
                    <td>82-86</td>
                </tr>
                <tr>
                    <th scope="row">L</th>
                    <td>98-102</td>
                    <td>86-90</td>
                </tr>
                <tr>
                    <th scope="row">XL</th>
                    <td>102-106</td>
                    <td>92-96</td>
                </tr>
                <tr>
                    <th scope="row">XXL</th>
                    <td>106-110</td>
                    <td>96-100</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Description',
        props: ['product'],
    }
</script>