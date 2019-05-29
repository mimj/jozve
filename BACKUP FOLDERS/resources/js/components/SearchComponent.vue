<template>
    <div>
        <input type="search" name="search" id="search">
        <button>search</button>
        <br>
        <br>
        <br>
        <SearchResultComponent></SearchResultComponent>
    </div>
</template>

<script>
    import SearchResultComponent from './SearchResultComponent'

    export default {
        name: "SearchComponent",
        components: {SearchResultComponent}
    }
</script>

<style scoped>

</style>