import {makeAutoObservable, runInAction} from "mobx";
import {ITest} from "../../types/types";
import $http from "../../utils/http";
import {storage} from "../../utils/tools";
import {TagOption} from "../../components/TagsForm/TagsFormStore";

export class NewTestStore {
    test: ITest
    loading: boolean = false

    constructor() {
        this.test.tags = []
        makeAutoObservable(this)
    }

    inputChange = (value: string, type: string) => {
        if(type === 'hour' || type === 'minute') {
            this.test.timeLimit[type] = value
        }
        this.test[type] = value
    }

    booleanChange = (value: boolean, type: string) => {
        this.test[type] = value
    }

    addTags = (tags: TagOption[]) => {
        tags.map(tag =>
            this.test.tags.push('api/tags/' + tag.id)
        )
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

    postNewTest = async () => {
        this.loading = true
        return $http.post('/api/tests', this.test)
            .then((res: any) => {
                return res.id
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