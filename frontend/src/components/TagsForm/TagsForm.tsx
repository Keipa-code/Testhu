import React, {useEffect} from 'react';
import {useRootStore} from "../../RootStateContext";
import {OnChangeValue} from "react-select";
import {TagOption} from "./TagsFormStore";
import CreatableSelect from "react-select/creatable";
import {observer} from "mobx-react-lite";

const TagsForm = observer(() => {
    const {tagsFormStore} = useRootStore()

    useEffect(() => {
        tagsFormStore.fetchTags()
    },[])

    const handleChange = (
        newValue: OnChangeValue<TagOption, true>
    ) => {
        tagsFormStore.addSelectedTags(newValue)
    }

    return (
        <CreatableSelect
            isMulti
            onChange={handleChange}
            options={tagsFormStore.tags}
        />
    );
});

export default TagsForm;