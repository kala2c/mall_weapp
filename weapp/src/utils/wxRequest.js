import wepy from 'wepy'
import tip from './tip'
// import env from './env'

// let token = wepy.getStorageSync(env.TOKEN) || ''
const wxRequest = async(url, params = {}, method) => {
  tip.loading()
  let data = params.query || {}
  let token = params.token || ''
  try {
    let res = await wepy.request({
      url: url,
      method: method || 'GET',
      data: data,
      header: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + token
      }
    })
    tip.loaded()
    return res
  } catch (error) {
    tip.loaded()
    tip.error('网络未连接！')
  }
}
module.exports = {
  wxRequest
}
