import React, {FC, useEffect} from 'react';
import {useRootStore} from "../../RootStateContext";
import {ActionMeta, OnChangeValue} from "react-select";
import {TagOption} from "./TagsFormStore";
import CreatableSelect from "react-select/creatable";
import {observer} from "mobx-react-lite";
import {Button} from "react-bootstrap";
import {NewTestStore} from "../../pages/NewTest/NewTestStore";
import AsyncCreatableSelect from "react-select/async-creatable";


interface TagsFormProps {
    addTags?: NewTestStore["addTags"];
}
const TagsForm: FC<TagsFormProps> = observer(({addTags}) => {
    const {tagsFormStore} = useRootStore()

    useEffect(() => {
        //tagsFormStore.fetchTags()
    }, [])

    const handleChange = (
        newValue: OnChangeValue<TagOption, true>,
        actionMeta: ActionMeta<TagOption>
    ) => {
        const id = (newValue.length - 1)
        tagsFormStore.addSelectedTags(newValue[id], actionMeta.action)
    }

    return (
        <div>
            <AsyncCreatableSelect
                className="mb-3"
                isMulti
                cacheOptions
                defaultOptions
                getOptionLabel={e => e.label}
                getOptionValue={e => String(e.id)}
                onChange={handleChange}
                loadOptions={tagsFormStore.fetchTags}
            />
        </div>
    );
});

export default TagsForm;