require('./config$');

function success() {
require('../..//app');
require('../..//pages/home/home');
require('../..//pages/sort/sort');
require('../..//pages/cart/cart');
require('../..//pages/items/items');
require('../..//pages/search/search');
require('../..//pages/setting/setting');
require('../..//pages/text/text');
require('../..//pages/application/application');
require('../..//pages/applications/morder/morder');
require('../..//pages/applications/mrate/mrate');
require('../..//pages/applications/orderdel/orderdel');
require('../..//pages/applications/ordertrack/ordertrack');
require('../..//pages/applications/frate/frate');
require('../..//pages/applications/maddress/maddress');
require('../..//pages/applications/newaddress/newaddress');
require('../..//pages/applications/aftersale/aftersale');
require('../..//pages/applications/obligation/obligation');
require('../..//pages/applications/waitgoods/waitgoods');
require('../..//pages/applications/waitrate/waitrate');
require('../..//pages/applications/refund/refund');
require('../..//pages/applications/waitpay/waitpay');
require('../..//pages/applications/map/map');
require('../..//pages/applications/collection/collection');
require('../..//pages/applications/mymessage/mymessage');
require('../..//pages/applications/nav/nav');
require('../..//pages/set/setphone/setphone');
require('../..//pages/set/setpwd/setpwd');
require('../..//pages/order/msorder/msorder');
require('../..//pages/order/orderlist/orderlist');
}
self.bootstrapApp ? self.bootstrapApp({ success }) : success();
