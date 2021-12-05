import { MessageApi } from 'antd/lib/message'
import { NotificationApi } from 'antd/lib/notification'
import { RouteComponentProps } from 'react-router-dom'
import { AxiosInstance } from 'axios'

declare global {
    export const $http: AxiosInstance

    export const $msg: MessageApi

    export const $notice: NotificationApi

    interface ICommonProps<P = AnyObject> extends RouteComponentProps<P>, AnyObject {
        [key: string]: any
    }

    interface IResponseData<T = any> {
        data: T
        msg: string
        status: number
    }

    type AnyObject<T = any> = Record<string, T>
}