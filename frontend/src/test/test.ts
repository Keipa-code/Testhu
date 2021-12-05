
interface responso<T = unknown, D = any> {
    data: T;
    status: number;
    statusText: string;
}

class HttpHelper {
    public successHelper(res: responso<any>): void {
        const {data: {errors}} = res
    }
}

const rese = {
    data: '123',
    status: 200,
    statusText: 'success'
}
const helper = new HttpHelper()

const error = helper.successHelper(rese)

console.log(error)