import * as ApiFavorite from '../api/Favorite';

export default {
  computed: {
    favorite: function () {
      return this.$store.getters.favorite;
    }
  },
  methods: {
    productAddToFavorite: function (id) {
      ApiFavorite.create({
        product_id: id,
        favorite_key: this.favorite.key
      }).then((res) => {
        if (res.data.status === 'success') {
          let favorite = this.favorite;
          favorite.products.unshift(res.data.favorite_product);
          this.$store.commit('updateFavorite', favorite);

          this.$notify({
            type: 'success',
            title: 'Товар',
            text: 'успешно добавлен в избранное'
          });
        }
      }).catch((error) => {
        this.alerts = error.response.data.errors;
        this.$notify({
          type: 'error',
          title: 'Ошибка',
          text: 'при добавлении товара в избранное'
        });
      });
    },
    productRemoveFromFavorite: function (id) {
      ApiFavorite.destroy({
        id: id,
        favorite_key: this.favorite.key
      }).then((res) => {
        if (res.data.status === 'success') {
          let favorite = this.favorite;
          let index = favorite.products.findIndex((item) => item.id === id);
          if (index !== -1) {
            favorite.products.splice(index, 1)
          }
          this.$store.commit('updateFavorite', favorite);

          this.$notify({
            type: 'success',
            title: 'Товар',
            text: 'успешно удален с избранного'
          });
        }
      }).catch((error) => {
        this.alerts = error.response.data.errors;
        this.$notify({
          type: 'error',
          title: 'Ошибка',
          text: 'при удалении товара с избранного'
        });
      });
    }
  }
}