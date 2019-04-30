<template>
	<el-row :gutter="12">
		<el-col :span="4" v-if="newOrders > 0">
			<router-link :to="{ name: 'orders-list' }">
				<el-badge :value="newOrders" class="item">
					<el-card shadow="never">
						<el-button type="text">
							<shopping-cart-icon class="custom-class"></shopping-cart-icon>
							Заказы
						</el-button>
					</el-card>
				</el-badge>
			</router-link>
		</el-col>
		<el-col :span="4" v-if="newSubscribes > 0">
			<router-link :to="{ name: 'subscribes-list' }">
				<el-badge :value="newSubscribes" class="item">
					<el-card shadow="never">
						<el-button type="text">
							<at-sign-icon class="custom-class"></at-sign-icon>
							Подписки по E-Mail
						</el-button>
					</el-card>
				</el-badge>
			</router-link>
		</el-col>
	</el-row>
</template>

<script>
	import { ShoppingCartIcon, AtSignIcon } from 'vue-feather-icons'

	export default {
        name: 'home',
		mounted() {
        	if (this.loadNotifications) {
				this.newSubscribes = this.newSubscribesStore;
				this.newOrders = this.newOrdersStore;
			}
		},
		computed: {
			loadNotifications: function () {
				return this.$store.getters.loadNotifications;
			},
			newSubscribesStore: function () {
				return this.$store.getters.newSubscribes;
			},
			newOrdersStore: function () {
				return this.$store.getters.newOrders;
			}
		},
		data() {
			return {
				newOrders: 0,
				newSubscribes: 0
			}
		},
		watch: {
			'newSubscribesStore': function (value) {
				this.newSubscribes = value;
			},
			'newOrdersStore': function (value) {
				this.newOrders = value;
			}
		},
		components: { ShoppingCartIcon, AtSignIcon }
    }
</script>
