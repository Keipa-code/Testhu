import axios from "axios";
import {action, computed, makeAutoObservable, observable, runInAction} from "mobx";
import {JWT_TOKEN, REDIRECT_URL} from "../constants";
import {storage} from "../utils/tools";

export interface token {
    token: string;
}

class TokenStore {
    @observable username: string = "frontend_anonymous"
    @observable password: string = "12345678"
    @observable loading: boolean = false

    @action inputChange = (value: string, type: string): void => {
        if (type === 'pwd') {
            this.password = value
        } else {
            this.username = value.trim()
        }
    }

    @computed get canLogin () {
        return this.password.length > 5 && this.username.length > 2
    }

    @action login = () => {
        if (this.loading) {
            return
        }
        if (!this.canLogin) {
            $msg.error('Слишком короткий логин или пароль')
            return
        }
        this.loading = true
        $http.post('/api/login', {
            username: this.username,
            password: this.password
        }).then((res: any) => {
            storage.set(JWT_TOKEN, res.data) // store jwt token
            window.location.replace(sessionStorage.getItem(REDIRECT_URL) || '/home')
            runInAction(() => {
                this.loading = false
            })
        }, err => {
            runInAction(() => {
                this.loading = false
            })
        })
    }
}

export default new TokenStore()