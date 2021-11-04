import {action, observable, runInAction} from "mobx";
import {ITest} from "../../types/types";
import $http from "../../utils/http";

export class NewTestStore {
    @observable test: ITest = {
        testName: ''
    }


    @action getDetail = (id: string) => {
        $http.get('/api/tests/' + id)
            .then((res: any) => {
                const {test} = res.data
                runInAction(() => {
                    this.test = test
                })
            })
    }
}