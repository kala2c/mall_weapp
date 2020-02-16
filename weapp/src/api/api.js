import { wxRequest } from '../utils/wxRequest'
const apidomian = 'https://chunyun.ldimay.cn/'  // 开发环境
// const apidomian = 'https://chunyun.ldimay.cn' // 生产环境
const jscode2session = params => wxRequest(apidomian + 'api/wechat/jscode2session', params, 'POST')
const bindwx = params => wxRequest(apidomian + 'api/wechat/bindwx', params, 'POST')
const selfinfo = params => wxRequest(apidomian + 'api/member/selfinfo', params)
const goodslist = params => wxRequest(apidomian + 'api/goods/list', params)
const goodsdetail = params => wxRequest(apidomian + 'api/goods/detail', params)
const exchange = params => wxRequest(apidomian + 'api/orders/exchange', params, 'POST')
const orderslist = params => wxRequest(apidomian + 'api/orders/list', params)
const confirm = params => wxRequest(apidomian + 'api/orders/confirm', params, 'POST')
const billlist = params => wxRequest(apidomian + 'api/member/billlist', params)

export default {
  jscode2session,
  bindwx,
  selfinfo,
  goodslist,
  goodsdetail,
  exchange,
  orderslist,
  confirm,
  billlist
}
