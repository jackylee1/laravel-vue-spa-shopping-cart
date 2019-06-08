<template>
  <div v-if="this.shouldShowPagination()">
    <div class="col-lg-12">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          <li v-if="!checkIsMobile"
              :class="(pagination.currentPage === 1) ? 'page-item disabled' : 'page-item'">
            <a href="javascript:void(0)"
               class="page-link"
               @click="pageClicked( pagination.currentPage - 1 )">
              Предыдущая
            </a>
          </li>
          <li class="page-item"
              v-for="page in this.pageLinks()"
              :key="page"
              :class="(isActive(page) || page === '...') ? 'page-item disabled' : 'page-item'">
            <a class="page-link"
               @click="pageClicked(page)"
               href="javascript:void(0)">
              {{ page }}
            </a>
          </li>
          <li v-if="!checkIsMobile"
              :class="(pagination.currentPage === this.pagination.totalPages) ? 'page-item disabled': 'page-item'">
            <a href="javascript:void(0)"
               class="page-link"
               @click="pageClicked( pagination.currentPage + 1 )">
              Следующая
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>
<script>
  import { isMobile } from 'mobile-device-detect';

  export default {
    name: 'Pagination',
    props: ['pagination'],
    computed: {
      checkIsMobile: function () {
        return isMobile;
      }
    },
    methods: {
      pageLinks: function () {
        if (this.pagination.totalPages === undefined) {
          return [];
        }

        const arr = [];
        let preDots = false;
        let postDots = false;

        for (let i = 1; i <= this.pagination.totalPages; i++) {
          if (this.pagination.totalPages <= 10) {
            arr.push(i);
          } else {
            if (i === 1) {
              arr.push(i);
            } else if (i === this.pagination.totalPages) {
              arr.push(i);
            } else if (
              (i > this.pagination.currentPage - 4 && i < this.pagination.currentPage + 4) ||
              (i < 4 && this.pagination.currentPage < 4) ||
              (i > this.pagination.totalPages - 4 && this.pagination.currentPage > this.pagination.totalPages - 4)) {
              arr.push(i);
            } else if (i < this.pagination.currentPage && !preDots) {
              arr.push('...');
              preDots = true;
            } else if (i > this.pagination.currentPage && !postDots) {
              arr.push('...');
              postDots = true;
            }
          }
        }

        return arr;
      },

      shouldShowPagination: function () {
        if (this.pagination.totalPages === undefined) {
          return false;
        }

        if (this.pagination.count === 0) {
          return false;
        }

        return this.pagination.totalPages > 1;
      },

      isActive: function (page) {
        const currentPage = this.pagination.currentPage || 1;

        return currentPage === page;
      },

      pageClicked: function (page) {
        if (page === '...' ||
          page === this.pagination.currentPage ||
          page > this.pagination.totalPages ||
          page < 1) {
          return;
        }



        this.$emit('pageChange', page);
        this.$scrollTo('#top_line', 450);
      },
    },
  };
</script>