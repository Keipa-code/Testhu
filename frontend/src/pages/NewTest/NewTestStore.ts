import { makeAutoObservable, runInAction } from "mobx";
import {ITest} from "../../types/types";
import $http from "../../utils/http";
import {storage} from "../../utils/tools";

export class NewTestStore {
    test: ITest = {
        testName: '',
        timeLimit: {
            hour: '',
            minute: ''
        }
    }

    constructor() {
        makeAutoObservable(this)
    }

    inputChange = (value: string, type: string) => {
        if(type === 'hour' || type === 'minute') {
            this.test.timeLimit[type] = value
        }
        this.test[type] = (type === 'password' || typeof(value) === 'number') ? value : value.trim()
    }

    getDetail = (id: string) => {
        $http.get<ITest>('/api/tests/' + id)
            .then((res: any) => {
                const data: ITest = res
                runInAction(() => {
                    this.test = data
                })
            })
    }

    getFromStorage = (key: string) => {
        const data = storage.get(key)
        if (data) {
            runInAction(() => {
                this.test = data
            })
        }
    }

    setToStorage = (key: string) => {
        runInAction(() => {
            storage.remove('key')
            storage.set(key, this.test)
        })
    }
}