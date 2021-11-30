import React from 'react';
import {makeAutoObservable, runInAction} from "mobx";
import $http from "../../utils/http";
import {IApiResponseCollection} from "../../types/types";


interface ITag {
    id: number;
    tagName: string;
}

export interface TagOption {
    readonly id: number;
    readonly label: string;
}

export class TagsFormStore {
    tags: TagOption[]
    selectedTags: TagOption[]

    constructor() {
        makeAutoObservable(this)
    }

    addSelectedTags = (selectedTags: any) => {
        this.selectedTags = selectedTags
    }

    fetchTags = () => {
        $http.get<ITag>('/api/tags/')
            .then((res: any) => {
                const data: IApiResponseCollection[] = res
                runInAction(() => {
                    data["hydra:member"].map((tag) => {
                        this.tags.push({
                            id: tag.id,
                            label: tag.tagName
                        })
                    })
                })
            })
    }

    postNewTags = (tagName: string) => {
        $http.post<ITag>('/api/tags', {
            tagName: tagName
        })
            .then((res: any) => {
                this.tags.push({
                    id: res.id,
                    label: tagName
                })
            })
    }
}