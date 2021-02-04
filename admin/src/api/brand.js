import request from '@/utils/request'
import Qs from 'qs'
export function getList(query) {
  return request({
    url: 'brand',
    method: 'get',
    params: query
  })
}

export function create(data) {
  data = Qs.parse({
    data
  })
  data = data.data
  return request({
    url: 'brand',
    method: 'post',
    data
  })
}

export function edit(data) {
  data = Qs.parse({
    data
  })
  data = data.data
  return request({
    url: 'brand/' + data.id,
    method: 'post',
    data
  })
}

export function destroy(id) {
  return request({
    url: 'brand/destroy/' + id,
    method: 'post'
  })
}
