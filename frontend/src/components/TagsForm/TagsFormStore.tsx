import React from 'react';
import {makeAutoObservable, runInAction} from "mobx";
import $http from "../../utils/http";
import {IApiResponseCollection} from "../../types/types";


interface ITag {
    id: number;
    tagName: string;
}

export interface TagOption {
    id?: number;
    label: string;
}

export class TagsFormStore {
    tags: TagOption[]
    selectedTags: TagOption[]

    constructor() {
        this.tags = []
        this.selectedTags = []
        makeAutoObservable(this)
    }

    addSelectedTags = async (selectedTags: TagOption, action: string) => {
        if(action == 'create-option') {
            this.postNewTags(selectedTags.label)
        } else {
            this.selectedTags.push(selectedTags)
        }
    }

    getTagsWithoutID = () => {
        return this.selectedTags.filter(selectedTag => !selectedTag.id)
    }

    fetchTags = () => {
        $http.get<ITag>('/api/tags')
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
                this.selectedTags.push({
                    id: res.id,
                    label: tagName
                })
            })
            .then(this.fetchTags)
    }
}