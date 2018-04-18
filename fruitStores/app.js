

App({
  todos: [
    { text: 'Learning Javascript', completed: true },
    { text: 'Learning ES2016', completed: true },
    { text: 'Learning 支付宝小程序', completed: false },
  ],
  userInfo: null,
  getUserInfo() {
    return new Promise((resolve, reject) => {
      if (this.userInfo) resolve(this.userInfo);

      my.getAuthCode({
        success: (authcode) => {
          console.info(authcode);

          my.getAuthUserInfo({
            scopes: ['auth_user'],
            success: (res) => {
              this.userInfo = res;
              resolve(this.userInfo);
            },
            fail: () => {
              reject({});
            },
          });
        },
        fail: () => {
          reject({});
        },
      });
    });
  },
  globalData:{
    navid:0,
    cartnum:0,
    navd:0,
    rePath:'http://192.168.199.197:8001/apiv1',
    //rePath:'http://192.168.199.120:8080/apiv1/url',
  }
});
