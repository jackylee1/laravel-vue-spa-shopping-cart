<template>
	<div style="">
		<div class="ds-block" style="min-width: 350px; max-width: 750px;margin: auto;" @keydown.enter="onSubmit">
			<el-form class="ds-source" ref="form" :model="form" label-width="120px">
				<el-form-item label="E-mail">
					<el-input type="email" v-model="form.email" placeholder="Введите E-mail"></el-input>
				</el-form-item>

				<el-form-item label="Пароль">
					<el-input type="password" v-model="form.password" placeholder="Введите Пароль"></el-input>
				</el-form-item>

				<el-form-item label="" v-if="error">
					<el-alert v-bind:title="error" type="error" :closable="false"></el-alert>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" @click="onSubmit">Войти</el-button>
				</el-form-item>
			</el-form>
		</div>
	</div>
</template>

<script>
	import {login} from '../../helpers/auth';

	export default {
		name: 'login',
		data() {
			return {
				form: {
					email: null,
					password: null
				},
				error: null
			}
		},
		methods: {
			onSubmit(evt) {
				evt.preventDefault();

				this.$store.dispatch('login');
                login(this.form).then((res) => {
                    this.$store.commit('loginSuccess', res);
                    this.$router.push({path: '/admin'});
                }).catch((error) => {
                    this.$store.commit('loginFailed', {error});
                    this.error = 'Вы ввели неверный E-mail или Пароль';
                })
			},

		}
	}
</script>
