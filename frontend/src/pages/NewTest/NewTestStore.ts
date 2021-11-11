import { makeAutoObservable, runInAction } from "mobx";
import {ITest} from "../../types/types";
import $http from "../../utils/http";

export class NewTestStore {
    test: ITest = {
        testName: ''
    }

    constructor() {
        makeAutoObservable(this)
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
}