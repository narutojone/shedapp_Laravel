/**
 * Communicates with API server about building history
 */

import Vue from 'vue'
import { csrfToken } from './_config'

const QrCodeResource = Vue.resource('/api/qrcodes',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodeStatusResource = Vue.resource('/api/qrcodes/status',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

const QrCodeImageUploadResource = Vue.resource('/api/qrcodes/files',
  {},
  {},
  {
    headers: {
      'X-CSRF-TOKEN': csrfToken()
    }
  }
)

export default {
  get ({ buildingId, query }) {
    return QrCodeResource.get({ buildingId, ...query })
  },
  getByIdentifier({ query }) {
    return Vue.http.get('/api/qrcodes/getbyidentifier', {
            params: query
        })
  },
  uploadFiles({ identifier, data }) {
      return QrCodeImageUploadResource.save({
        identifier: identifier
      }, data)
  },
  save ({ item, data }) {
      return QrCodeResource.save({
        building_id: item.buildingId
      }, data)
  },
  savestatus ({ item, data }) {
      return QrCodeStatusResource.save({
        building_id: item.buildingId
      }, data)
  },
  delete({ item }) {
    return QrCodeResource.delete({
      building_id: item.buildingId,
      id: item.id
    })
  }
}
